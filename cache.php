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
 * Returns a new context object
 *
 * @param array $confPaths List of configuration paths from the bootstrap object
 * @param array $options Associative list of configuration options as key/value pairs
 * @return \Aimeos\MShop\Context\Item\Iface Context object
 */
function getContext( array $confPaths, array $options )
{
	$config = array();
	$ctx = new \Aimeos\MShop\Context\Item\Standard();

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

	$conf = new \Aimeos\MW\Config\PHPArray( $config, $confPaths );
	$conf = new \Aimeos\MW\Config\Decorator\Memory( $conf );
	$ctx->setConfig( $conf );

	$dbm = new \Aimeos\MW\DB\Manager\PDO( $conf );
	$ctx->setDatabaseManager( $dbm );

	$logger = new \Aimeos\MW\Logger\Errorlog( \Aimeos\MW\Logger\Base::INFO );
	$ctx->setLogger( $logger );

	return $ctx;
}


/**
 * Returns the locale site items for the given site code string
 *
 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
 * @param string|null $sites List of site codes separated by a space
 */
function getSiteItems( \Aimeos\MShop\Context\Item\Iface $ctx, $sites )
{
	$manager = \Aimeos\MShop\Factory::createManager( $ctx, 'locale/site' );
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
 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
 * @param array $siteItems List of site items implementing \Aimeos\MShop\Locale\Site\Iface
 */
function clear( \Aimeos\MShop\Context\Item\Iface $ctx, array $siteItems )
{
	$localeManager = \Aimeos\MShop\Factory::createManager( $ctx, 'locale' );

	foreach( $siteItems as $siteItem )
	{
		$localeItem = $localeManager->bootstrap( $siteItem->getCode(), '', '', false );

		$lcontext = clone $ctx;
		$lcontext->setLocale( $localeItem );

		$cache = new \Aimeos\MAdmin\Cache\Proxy\Standard( $lcontext );
		$lcontext->setCache( $cache );

		printf( "Clearing the Aimeos cache for site \"%1\$s\"\n", $siteItem->getCode() );

		\Aimeos\MAdmin\Cache\Manager\Factory::createManager( $lcontext )->getCache()->flush();
	}
}


/**
 * Prints the command usage and options, exits the program after printing
 */
function usage()
{
	printf( "Usage: php cache.php [--extdir=<path>]* [--config=<path>|<file>]* [\"sitecode1 [sitecode2]*\"]\n" );
	exit( 1 );
}


try
{
	$params = $_SERVER['argv'];
	array_shift( $params );

	$options = getOptions( $params );
	$sites = array_shift( $params );

	require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

	$aimeos = new \Aimeos\Bootstrap( ( isset( $options['extdir'] ) ? (array) $options['extdir'] : array() ) );
	$ctx = getContext( $aimeos->getConfigPaths(), $options );
	$siteItems = getSiteItems( $ctx, $sites );

	clear( $ctx, $siteItems );
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
