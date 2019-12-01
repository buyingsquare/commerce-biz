<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Base
 */

namespace Aimeos\Slim\Base;

use Psr\Container\ContainerInterface;


/**
 * Service providing the internationalization objects
 *
 * @package Slim
 * @subpackage Base
 */
class I18n
{
	private $container;
	private $i18n = array();


	/**
	 * Initializes the object
	 *
	 * @param ContainerInterface $container Dependency container
	 */
	public function __construct( ContainerInterface $container )
	{
		$this->container = $container;
	}


	/**
	 * Creates new translation objects.
	 *
	 * @param array $languageIds List of two letter ISO language IDs
	 * @return \Aimeos\MW\Translation\Iface[] List of translation objects
	 */
	public function get( array $languageIds )
	{
		$config = $this->container->get( 'aimeos.config' )->get();
		$i18nPaths = $this->container->get( 'aimeos' )->getI18nPaths();

		foreach( $languageIds as $langid )
		{
			if( !isset( $this->i18n[$langid] ) )
			{
				$i18n = new \Aimeos\MW\Translation\Gettext( $i18nPaths, $langid );

				if( function_exists( 'apcu_store' ) === true && $config->get( 'apc_enabled', false ) == true ) {
					$i18n = new \Aimeos\MW\Translation\Decorator\APC( $i18n, $config->get( 'apc_prefix', 'slim:' ) );
				}

				if( ( $cfg = $config->get( 'i18n/' . $langid, array() ) ) !== array() ) {
					$i18n = new \Aimeos\MW\Translation\Decorator\Memory( $i18n, $cfg );
				}

				$this->i18n[$langid] = $i18n;
			}
		}

		return $this->i18n;
	}
}