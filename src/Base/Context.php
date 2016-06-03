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
 * Service providing the context objects
 *
 * @package Slim
 * @subpackage Base
 */
class Context
{
	private static $context;
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
	 * Returns the current context
	 *
	 * @param boolean $locale True to add locale object to context, false if not
	 * @param array Associative list of URL parameter
	 * @return \Aimeos\MShop\Context\Item\Iface Context object
	 */
	public function get( $locale = true, array $attributes = array() )
	{
		if( self::$context === null )
		{
			$context = new \Aimeos\MShop\Context\Item\Standard();

			$config = $this->container->get( 'aimeos_config' );
			$context->setConfig( $config );

			$dbm = new \Aimeos\MW\DB\Manager\DBAL( $config );
			$context->setDatabaseManager( $dbm );

			$fs = new \Aimeos\MW\Filesystem\Manager\Standard( $config );
			$context->setFilesystemManager( $fs );

			$mq = new \Aimeos\MW\MQueue\Manager\Standard( $config );
			$context->setMessageQueueManager( $mq );

			$mail = new \Aimeos\MW\Mail\Swift( $this->container->get( 'mailer' ) );
			$context->setMail( $mail );

			$logger = \Aimeos\MAdmin\Log\Manager\Factory::createManager( $context );
			$context->setLogger( $logger );

			$cache = new \Aimeos\MAdmin\Cache\Proxy\Standard( $context );
			$context->setCache( $cache );

			$session = new \Aimeos\MW\Session\PHP();
			$context->setSession( $session );

			self::$context = $context;
		}

		$context = self::$context;

		if( $locale === true )
		{
			$localeItem = $this->getLocale( $context, $attributes );
			$langid = $localeItem->getLanguageId();

			$context->setLocale( $localeItem );
			$context->setI18n( $this->container->get( 'aimeos_i18n' )->get( array( $langid ) ) );
		}

		return $context;
	}


	/**
	 * Returns the locale item for the current request
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param array Associative list of URL parameter
	 * @return \Aimeos\MShop\Locale\Item\Iface Locale item object
	 */
	protected function getLocale( \Aimeos\MShop\Context\Item\Iface $context, array $attr )
	{
		if( $this->locale === null )
		{
			$disableSites = $this->container->get( 'aimeos_config' )->get( 'disableSites', true );

			$site = ( isset( $attr['site'] ) ? $attr['site'] : 'default' );
			$lang = ( isset( $attr['locale'] ) ? $attr['locale'] : '' );
			$currency = ( isset( $attr['currency'] ) ? $attr['currency'] : '' );

			$localeManager = \Aimeos\MShop\Locale\Manager\Factory::createManager( $context );
			$this->locale = $localeManager->bootstrap( $site, $lang, $currency, $disableSites );
		}

		return $this->locale;
	}
}
