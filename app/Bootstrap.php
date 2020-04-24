<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 */

namespace Aimeos\Slim;

use Dotenv\Dotenv;

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
	public function __construct( \Slim\App $app, Dotenv $env)
	{
        require __DIR__ . DIRECTORY_SEPARATOR . 'aimeos-default.php';
        require __DIR__ . DIRECTORY_SEPARATOR . 'aimeos-settings.php';

		$this->app = $app;
		$this->settings = __aimeos_settings__($env);
	}


	/**
	 * Registers the Aimeos routes
	 *
	 * @param string $path Absolute or relative path to the Aimeos route file
	 * @return \Aimeos\Slim\Bootstrap Self instance
	 */
	public function routes( $path ) : self
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
	public function setup( $extdir = '../ext' ) : self
	{
		$container = $this->app->getContainer();

		$container['router'] = function( $c ) {
			return new \Aimeos\Slim\Router();
		};

		$container['mailer'] = function( $c ) {
			return new \Swift_Mailer( new \Swift_SendmailTransport() );
		};

		$default = __aimeos_default__();
		$settings = array_replace_recursive( $default, $this->settings );

		$container['aimeos'] = function( $c ) use ( $extdir ) {
			return new \Aimeos\Bootstrap( (array) $extdir, false );
		};

		$container['aimeos.config'] = function( $c ) use ( $settings ) {
			return new \Aimeos\Slim\Base\Config( $c, $settings );
		};

		$container['aimeos.context'] = function( $c ) {
			return new \Aimeos\Slim\Base\Context( $c );
		};

		$container['aimeos.i18n'] = function( $c ) {
			return new \Aimeos\Slim\Base\I18n( $c );
		};

		$container['aimeos.locale'] = function( $c ) {
			return new \Aimeos\Slim\Base\Locale( $c );
		};

		$container['aimeos.view'] = function( $c ) {
			return new \Aimeos\Slim\Base\View( $c );
		};

		$container['shop'] = function( $c ) {
			return new \Aimeos\Slim\Base\Shop( $c );
		};

		// add client IP address to requests
		$this->app->add( new \RKA\Middleware\IpAddress( true, [] ) );

		return $this;
	}

    /**
     * Returns the version of the Buyingsquare package
     *
     * @return string Version string
     */
    public static function getVersion() : string
    {
        $basedir = dirname( dirname( dirname( dirname( __DIR__ ) ) ) ) . DIRECTORY_SEPARATOR;

        if( ( $content = @file_get_contents( $basedir . 'composer.lock' ) ) !== false
            && ( $content = json_decode( $content, true ) ) !== null && isset( $content['packages'] )
        ) {
            foreach( (array) $content['packages'] as $item )
            {
                if( $item['name'] === 'buyingsquare/commerce-biz' ) {
                    return $item['version'];
                }
            }
        }

        return '';
    }

}
