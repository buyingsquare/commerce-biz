<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Command
 */

namespace Aimeos\Slim\Command;


/**
 * Aimeos cache command class
 *
 * @package Slim
 * @subpackage Command
 */
class Cache extends Base implements Iface
{
	/**
	 * Returns the command usage and options
	 *
	 * @return string Command usage and options
	 */
	public static function usage()
	{
		return "Usage: php cache.php [--extdir=<path>]* [--config=<path>|<file>]* [\"sitecode1 [sitecode2]*\"]\n";
	}


	/**
	 * Executes the command
	 *
	 * @param array $argv Associative array from $_SERVER['argv']
	 */
	public static function run( array $argv )
	{
		array_shift( $argv );
		$options = self::getOptions( $argv );
		$sites = array_shift( $argv );

		$extdirs = ( isset( $options['extdir'] ) ? (array) $options['extdir'] : array() );
		$aimeos = new \Aimeos\Bootstrap( $extdirs );

		$ctx = self::getContext( $aimeos->getConfigPaths(), $options );
		$siteItems = self::getSiteItems( $ctx, $sites );

		self::clear( $ctx, $siteItems );
	}


	/**
	 * Returns a new context object
	 *
	 * @param array $confPaths List of configuration paths from the bootstrap object
	 * @param array $options Associative list of configuration options as key/value pairs
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	protected static function getContext( array $confPaths, array $options )
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
	 * Removes the cached data for the given sites
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
	 * @param array $siteItems List of site items implementing \Aimeos\MShop\Locale\Site\Iface
	 */
	protected static function clear( \Aimeos\MShop\Context\Item\Iface $ctx, array $siteItems )
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
}