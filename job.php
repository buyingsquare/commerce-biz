<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 */


if( php_sapi_name() != 'cli' ) {
	exit( 'Setup can only be started via command line for security reasons' );
}

ini_set( 'display_errors', 1 );
date_default_timezone_set( 'UTC' );



/**
 * Returns the command options given by the user
 *
 * @param array &$params List of parameters
 * @return array Associative list of option name and value(s)
 */
function getOptions( array &$params )
{
	$options = array();

	foreach( $params as $key => $option )
	{
		if( $option === '--help' ) {
			usage();
		}

		if( strncmp( $option, '--', 2 ) === 0 && ( $pos = strpos( $option, '=', 2 ) ) !== false )
		{
			if( ( $name = substr( $option, 2, $pos - 2 ) ) !== false )
			{
				if( isset( $options[$name] ) )
				{
					$options[$name] = (array) $options[$name];
					$options[$name][] = substr( $option, $pos + 1 );
				}
				else
				{
					$options[$name] = substr( $option, $pos + 1 );
				}

				unset( $params[$key] );
			}
			else
			{
				printf( "Invalid option \"%1\$s\"\n", $option );
				usage();
			}
		}
	}

	return $options;
}


/**
 * Returns the configuration based on the given options
 *
 * @param array $options Associative list of given options
 * @return array Multi-dimensional array of configuration settings
 */
function getConfig( array $options )
{
	$config = array();

	if( isset( $options['config'] ) )
	{
		foreach( (array) $options['config'] as $path )
		{
			if( is_file( $path ) ) {
				$config = array_replace_recursive( $config, require $path );
			} else {
				$confPaths[] = $path;
			}
		}
	}

	return $config;
}


/**
 * Returns a new context object
 *
 * @param \Interop\Container\ContainerInterface $container Dependency injection container
 * @return \Aimeos\MShop\Context\Item\Standard Context object
 */
function getContext( \Interop\Container\ContainerInterface $container )
{
	$aimeos = $container->get( 'aimeos' );
	$context = $container->get( 'aimeos_context' )->get( false );

	$env = \Slim\Http\Environment::mock();
	$request = \Slim\Http\Request::createFromEnvironment( $env );
	$response = new \Slim\Http\Response();

	$tmplPaths = $aimeos->getCustomPaths( 'controller/jobs/templates' );
	$view = $container->get( 'aimeos_view' )->create( $request, $response, array(), $tmplPaths );

	$langManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/language' );
	$langids = array_keys( $langManager->searchItems( $langManager->createSearch( true ) ) );
	$i18n = $container->get( 'aimeos_i18n' )->get( $langids );

	$context->setEditor( 'aimeos:jobs' );
	$context->setView( $view );
	$context->setI18n( $i18n );

	return $context;
}


/**
 * Returns the locale site items for the given site code string
 *
 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
 * @param string|null $sites List of site codes separated by a space
 */
function getSiteItems( \Aimeos\MShop\Context\Item\Iface $ctx, $sites )
{
	$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $ctx );
	$manager = $localeManager->getSubManager( 'site' );
	$search = $manager->createSearch();

	if( is_scalar( $sites ) && $sites != '' ) {
		$sites = explode( ' ', $sites );
	}

	if( !empty( $sites ) ) {
		$search->setConditions( $search->compare( '==', 'locale.site.code', $sites ) );
	}

	return $manager->searchItems( $search );
}


/**
 * Removes the cached data for the given sites
 *
 * @param \Aimeos\Bootstrap $aimeos Aimeos bootstrap object
 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
 * @param array $siteItems List of site items implementing \Aimeos\MShop\Locale\Site\Iface
 */
function execute( \Aimeos\Bootstrap $aimeos, \Aimeos\MShop\Context\Item\Iface $ctx, array $siteItems, $jobs )
{
	$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $ctx );

	foreach( $siteItems as $siteItem )
	{
		$localeItem = $localeManager->bootstrap( $siteItem->getCode(), 'en', '', false );
		$ctx->setLocale( $localeItem );

		printf( "Executing the Aimeos jobs for \"%s\"\n", $siteItem->getCode() );

		foreach( (array) explode( ' ', $jobs ) as $jobname ) {
			\Aimeos\Controller\Jobs\Factory::createController( $ctx, $aimeos, $jobname )->run();
		}
	}
}


/**
 * Prints the command usage and options, exits the program after printing
 */
function usage()
{
	printf( "Usage: php job.php [--extdir=<path>]* [--config=<path>|<file>]* \"job1 [job2]*\" [\"sitecode1 [sitecode2]*\"]\n" );
	exit( 1 );
}



try
{
	$params = $_SERVER['argv'];
	array_shift( $params );

	$options = getOptions( $params );

	if( ( $jobs = array_shift( $params ) ) === null ) {
		usage();
	}

	$sites = array_shift( $params );
	$config = getConfig( $options );

	require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

	$app = new \Slim\App( $config );
	$aimeos = new \Aimeos\Slim\Bootstrap( $app, $config );
	$aimeos->setup( ( isset( $options['extdir'] ) ? $options['extdir'] : './ext' ) );

	$container = $app->getContainer();
	$context = getContext( $container );

	$siteItems = getSiteItems( $context, $sites );
	execute( $container->get( 'aimeos' ), $context, $siteItems, $jobs );
}
catch( \Throwable $t )
{
	echo "\n\nCaught PHP error while processing setup";
	echo "\n\nMessage:\n";
	echo $t->getMessage();
	echo "\n\nStack trace:\n";
	echo $t->getTraceAsString();
	echo "\n\n";
	exit( 1 );
}
catch( \Exception $e )
{
	echo "\n\nCaught exception while processing setup";
	echo "\n\nMessage:\n";
	echo $e->getMessage();
	echo "\n\nStack trace:\n";
	echo $e->getTraceAsString();
	echo "\n\n";
	exit( 1 );
}
