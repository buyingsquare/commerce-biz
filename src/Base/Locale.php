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
 * Service providing the locale objects
 *
 * @package Slim
 * @subpackage Base
 */
class Locale
{
	private $container;
	private $locale;


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
	 * Returns the locale item for the current request
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param array $attributes Associative list of URL parameter
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public function get( \Aimeos\MShop\Context\Item\Iface $context, array $attributes ) : \Aimeos\MShop\Locale\Item\Iface
	{
		if( $this->locale === null )
		{
			$disableSites = $this->container->get( 'aimeos.config' )->get()->get( 'disableSites', true );
print_r( $disableSites );

			$site = ( isset( $attributes['site'] ) ? $attributes['site'] : 'default' );
			$lang = ( isset( $attributes['locale'] ) ? $attributes['locale'] : '' );
			$currency = ( isset( $attributes['currency'] ) ? $attributes['currency'] : '' );

			$localeManager = \Aimeos\MShop::create( $context, 'locale' );
			$this->locale = $localeManager->bootstrap( $site, $lang, $currency, $disableSites );
		}

		return $this->locale;
	}


	/**
	 * Returns the locale item for the current request
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $site Unique site code
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	public function getBackend( \Aimeos\MShop\Context\Item\Iface $context, string $site ) : \Aimeos\MShop\Locale\Item\Iface
	{
		$localeManager = \Aimeos\MShop::create( $context, 'locale' );

		try {
			$localeItem = $localeManager->bootstrap( $site, '', '', false, null, true );
		} catch( \Aimeos\MShop\Exception $e ) {
			$localeItem = $localeManager->createItem();
		}

		return $localeItem->setCurrencyId( null )->setLanguageId( null );
	}
}
