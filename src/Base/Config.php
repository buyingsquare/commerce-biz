<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Base
 */

namespace Aimeos\Slim\Base;

use Interop\Container\ContainerInterface;


/**
 * Service providing the config objects
 *
 * @package Slim
 * @subpackage Base
 */
class Config
{
	private $container;
	private $settings;


	/**
	 * Initializes the object
	 *
	 * @param ContainerInterface $container Dependency container
	 */
	public function __construct( ContainerInterface $container, $settings )
	{
		$this->container = $container;
		$this->settings = $settings;
	}


	/**
	 * Returns the config object
	 *
	 * @param string $type Configuration type ("frontend" or "backend")
	 * @return \Aimeos\MW\Config\Iface Config object
	 */
	public function get( $type = 'frontend' )
	{
		$paths = $this->container->get( 'aimeos' )->getConfigPaths();
		$config = new \Aimeos\MW\Config\PHPArray( array(), $paths );

		if( function_exists( 'apcu_store' ) === true && $config->get( 'apc_enabled', false ) == true ) {
			$config = new \Aimeos\MW\Config\Decorator\APC( $config, $config->get( 'apc_prefix', 'slim:' ) );
		}

		$config = new \Aimeos\MW\Config\Decorator\Memory( $config, $this->settings );

		if( isset( $this->settings[$type] ) ) {
			$config = new \Aimeos\MW\Config\Decorator\Memory( $config, $this->settings[$type] );
		}

		return $config;
	}
}
