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
		$app = new \Slim\App( array(
			'settings' => array( 'determineRouteBeforeAppMiddleware' => true )
		) );

		$settings = require dirname( __DIR__ ) . '/src/aimeos-settings.php';
		$settings['routes'] = array(
			'admin' => '/{site}/',
			'account' => '/{site}/',
			'default' => '/{site}/',
			'confirm' => '/{site}/',
			'update' => '/{site}/',
		);

		$boot = new \Aimeos\Slim\Bootstrap( $app, $settings );
		$boot->setup( dirname( __DIR__ ) . '/ext' )->routes( dirname( __DIR__ ) . '/src/aimeos-routes.php' );

		$c = $app->getContainer();
		$env = \Slim\Http\Environment::mock( array(
			'REQUEST_URI' => $path,
			'QUERY_STRING' => http_build_query( $params )
		));
		$c['request'] = \Slim\Http\Request::createFromEnvironment( $env );
		$c['response'] = new \Slim\Http\Response();

		$twigconf = array( 'cache' => sys_get_temp_dir() . '/aimeos-slim-twig-cache' );
		$c['view'] = new \Slim\Views\Twig( dirname( __DIR__ ) . '/templates', $twigconf );
		$c['view']->addExtension( new \Slim\Views\TwigExtension( $c['router'], $c['request']->getUri() ) );

		return $app->run( true );
	}
};
