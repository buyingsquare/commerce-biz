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
class Jobs extends Base implements Iface
{
	/**
	 * Returns the command usage and options
	 *
	 * @return string Command usage and options
	 */
	public static function usage()
	{
		return "Usage: php job.php [--extdir=<path>]* [--config=<path>|<file>]* [--routes=<file>] \"job1 [job2]*\" [\"sitecode1 [sitecode2]*\"]\n";
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

		if( ( $jobs = array_shift( $argv ) ) === null ) {
			throw new \Aimeos\Slim\Command\Exception();
		}
		$sites = array_shift( $argv );

		$config = self::getConfig( $options );

		$app = new \Slim\App( $config );
		$aimeos = new \Aimeos\Slim\Bootstrap( $app, $config );
		$aimeos->setup( ( isset( $options['extdir'] ) ? $options['extdir'] : './ext' ) )
			->routes( ( isset( $options['routes'] ) ? $options['routes'] : './src/aimeos-routes.php' ) );

		$container = $app->getContainer();
		$context = self::getContext( $container );

		$siteItems = self::getSiteItems( $context, $sites );
		self::execute( $container->get( 'aimeos' ), $context, $siteItems, $jobs );
	}


	/**
	 * Returns the configuration based on the given options
	 *
	 * @param array $options Associative list of given options
	 * @return array Multi-dimensional array of configuration settings
	 */
	protected static function getConfig( array $options )
	{
		$config = array();

		if( isset( $options['config'] ) )
		{
			foreach( (array) $options['config'] as $path )
			{
				if( is_file( $path ) ) {
					$config = array_replace_recursive( $config, require $path );
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
	protected static function getContext( \Interop\Container\ContainerInterface $container )
	{
		$aimeos = $container->get( 'aimeos' );
		$context = $container->get( 'aimeos_context' )->get( false, array(), 'command' );

		$env = \Slim\Http\Environment::mock();
		$request = \Slim\Http\Request::createFromEnvironment( $env );
		$response = new \Slim\Http\Response();

		$tmplPaths = $aimeos->getCustomPaths( 'controller/jobs/templates' );
		$view = $container->get( 'aimeos_view' )->create( $context, $request, $response, array(), $tmplPaths );

		$langManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/language' );
		$langids = array_keys( $langManager->searchItems( $langManager->createSearch( true ) ) );
		$i18n = $container->get( 'aimeos_i18n' )->get( $langids );

		$context->setEditor( 'aimeos:jobs' );
		$context->setView( $view );
		$context->setI18n( $i18n );

		return $context;
	}


	/**
	 * Removes the cached data for the given sites
	 *
	 * @param \Aimeos\Bootstrap $aimeos Aimeos bootstrap object
	 * @param \Aimeos\MShop\Context\Item\Iface $ctx Context object
	 * @param array $siteItems List of site items implementing \Aimeos\MShop\Locale\Site\Iface
	 */
	protected static function execute( \Aimeos\Bootstrap $aimeos, \Aimeos\MShop\Context\Item\Iface $ctx, array $siteItems, $jobs )
	{
		$process = $ctx->getProcess();
		$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $ctx );

		foreach( $siteItems as $siteItem )
		{
			$localeItem = $localeManager->bootstrap( $siteItem->getCode(), 'en', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );

			$ctx->setLocale( $localeItem );

			printf( "Executing the Aimeos jobs for \"%s\"\n", $siteItem->getCode() );

			foreach( (array) explode( ' ', $jobs ) as $jobname )
			{
				$fcn = function( $ctx, $aimeos, $jobname ) {
					\Aimeos\Controller\Jobs\Factory::createController( $ctx, $aimeos, $jobname )->run();
				};

				$process->start( $fcn, [$ctx, $aimeos, $jobname], true );
			}
		}

		$process->wait();
	}
}
