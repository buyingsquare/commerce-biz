<?php

setlocale( LC_ALL, 'en_US.UTF-8' );

// if the bundle is within a Slim project, try to reuse the project's autoload
$files = array(
	__DIR__ . '/../vendor/autoload.php',
	__DIR__ . '/../../vendor/autoload.php',
);

$autoload = false;
foreach( $files as $file )
{
	if( is_file( $file ) ) {
		$autoload = include_once $file;
		break;
	}
}

if( !$autoload )
{
	exit(
		"Unable to find autoload.php file, please use composer to load dependencies:
		wget http://getcomposer.org/composer.phar
		php composer.phar install
		Visit http://getcomposer.org/ for more information.\n"
	);
}


class LocalWebTestCase extends \PHPUnit_Framework_TestCase
{
	public function call( $method, $path, $params = array() )
	{
		$app = new \Slim\App([
			'settings' => [
				'determineRouteBeforeAppMiddleware' => true
			]
		]);

		$c = $app->getContainer();
		$env = \Slim\Http\Environment::mock( array(
			'REQUEST_URI' => $path,
			'QUERY_STRING' => http_build_query( $params )
		));
		$c['request'] = \Slim\Http\Request::createFromEnvironment( $env );
		$c['response'] = new Slim\Http\Response();

		$twigconf = array( 'cache' => sys_get_temp_dir() . '/aimeos-slim-twig-cache' );
		$c['view'] = new \Slim\Views\Twig( dirname( __DIR__ ) . '/templates', $twigconf );
		$c['view']->addExtension( new \Slim\Views\TwigExtension( $c['router'], $c['request']->getUri() ) );

		$boot = new \Aimeos\Slim\Bootstrap( $app, require dirname( __DIR__ ) . '/src/settings.php' );
		$boot->routes( dirname( __DIR__ ) . '/src/routes.php' )->setup( dirname( __DIR__ ) . '/ext' );

		return $app->run( true );
	}
};
