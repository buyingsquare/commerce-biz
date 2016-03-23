<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 */


namespace Aimeos\Slim;


/**
 * Bootstrap class for Aimeos Slim integration
 *
 * @package Slim
 */
class Bootstrap
{
	private $app;
	private $settings;


	/**
	 * Initializes the object
	 *
	 * @param \Slim\App $app Slim application
	 * @param array $settings Multi-dimensional array of configuration settings
	 */
	public function __construct( \Slim\App $app, array $settings )
	{
		$this->app = $app;
		$this->settings = $settings;
	}


	/**
	 * Registers the Aimeos routes
	 *
	 * @param string $path Absolute or relative path to the Aimeos route file
	 * @return \Aimeos\Slim\Bootstrap Self instance
	 */
	public function routes( $path )
	{
		$app = $this->app;
		$settings = $this->settings;

		$config = function( $key, $default ) use ( $settings )
		{
			foreach( explode( '/', trim( $key, '/' ) ) as $part )
			{
				if( isset( $settings[$part] ) ) {
					$settings = $settings[$part];
				} else {
					return $default;
				}
			}

			return $settings;
		};

		require $path;

		return $this;
	}


	/**
	 * Sets up the Aimeos environemnt
	 *
	 * @param string $extdir Absolute or relative path to the Aimeos extension directory
	 * @return \Aimeos\Slim\Bootstrap Self instance
	 */
	public function setup( $extdir = '../ext' )
	{
		$default = require __DIR__ . DIRECTORY_SEPARATOR . 'aimeos-default.php';
		$settings = array_replace_recursive( $default, $this->settings );
		$container = $this->app->getContainer();

		$container['aimeos'] = function( $c ) use ( $extdir ) {
			return new \Aimeos\Bootstrap( (array) $extdir, false );
		};

		$container['aimeos_context'] = function( $c ) {
			return new \Aimeos\Slim\Base\Context( $c );
		};

		$container['aimeos_i18n'] = function( $c ) {
			return new \Aimeos\Slim\Base\I18n( $c );
		};

		$container['aimeos_page'] = function( $c ) {
			return new \Aimeos\Slim\Base\Page( $c );
		};

		$container['aimeos_view'] = function( $c ) {
			return new \Aimeos\Slim\Base\View( $c );
		};


		$container['aimeos_config'] = function( $c ) use ( $settings ) {

			$config = new \Aimeos\MW\Config\PHPArray( $settings, $c['aimeos']->getConfigPaths() );

			if( function_exists( 'apc_store' ) === true && $config->get( 'apc_enabled', false ) == true ) {
				$config = new \Aimeos\MW\Config\Decorator\APC( $config, $config->get( 'apc_prefix', 'slim:' ) );
			}

			return $config;
		};


		$container['mailer'] = function( $c ) {
			return \Swift_Mailer::newInstance( \Swift_SendmailTransport::newInstance() );
		};

		return $this;
	}


	/**
	 * Returns the version of the Aimeos package
	 *
	 * @return string Version string
	 */
	public static function getVersion()
	{
		$basedir = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . DIRECTORY_SEPARATOR;

		if( ( $content = @file_get_contents( $basedir . 'composer.lock' ) ) !== false
			&& ( $content = json_decode( $content, true ) ) !== null && isset( $content['packages'] )
		) {
			foreach( (array) $content['packages'] as $item )
			{
				if( $item['name'] === 'aimeos/aimeos-slim' ) {
					return $item['version'];
				}
			}
		}

		return '';
	}
}