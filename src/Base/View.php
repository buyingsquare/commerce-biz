<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Base
 */

namespace Aimeos\Slim\Base;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Service providing the view objects
 *
 * @package Slim
 * @subpackage Base
 */
class View
{
	private $container;


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
	 * Creates the view object for the HTML client.
	 *
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $attr Associative list of URI parameters
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param string|null $locale Code of the current language or null for no translation
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public function create( ServerRequestInterface $request, ResponseInterface $response, array $attr, array $templatePaths, $locale = null )
	{
		$params = $fixed = array();
		$config = $this->container->get( 'aimeos_config' );

		if( $locale !== null )
		{
			$params = $attr + (array) $request->getParsedBody() + (array) $request->getQueryParams();
			$fixed = $this->getFixedParams( $attr );

			$i18n = $this->container->get( 'aimeos_i18n' )->get( array( $locale ) );
			$translation = $i18n[$locale];
		}
		else
		{
			$translation = new \Aimeos\MW\Translation\None( 'en' );
		}


		$view = new \Aimeos\MW\View\Standard( $templatePaths );

		$helper = new \Aimeos\MW\View\Helper\Translate\Standard( $view, $translation );
		$view->addHelper( 'translate', $helper );

		$helper = new \Aimeos\MW\View\Helper\Url\Slim( $view, $this->container->get( 'router' ), $fixed );
		$view->addHelper( 'url', $helper );

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $params );
		$view->addHelper( 'param', $helper );

		$config = new \Aimeos\MW\Config\Decorator\Protect( clone $config, array( 'admin', 'client' ) );
		$helper = new \Aimeos\MW\View\Helper\Config\Standard( $view, $config );
		$view->addHelper( 'config', $helper );

		$sepDec = $config->get( 'client/html/common/format/seperatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/seperator1000', ' ' );
		$helper = new \Aimeos\MW\View\Helper\Number\Standard( $view, $sepDec, $sep1000 );
		$view->addHelper( 'number', $helper );

		$helper = new \Aimeos\MW\View\Helper\Request\Slim( $view, $request );
		$view->addHelper( 'request', $helper );

		$helper = new \Aimeos\MW\View\Helper\Response\Slim( $view, $response );
		$view->addHelper( 'response', $helper );

		$csrf = $request->getAttribute( 'csrf_name' );
		$helper = new \Aimeos\MW\View\Helper\Csrf\Standard( $view, $csrf, $request->getAttribute( 'csrf_value ') );
		$view->addHelper( 'csrf', $helper );

		$helper = new \Aimeos\MW\View\Helper\Access\Standard( $view, $this->getGroups( $context ) );
		$view->addHelper( 'access', $helper );

		return $view;
	}


	/**
	 * Returns the routing parameters passed in the URL
	 *
	 * @param array $attributes Associative list of route attributes
	 * @return array Associative list of parameters with "site", "locale" and "currency" if available
	 */
	protected function getFixedParams( array $attributes )
	{
		$fixed = array();

		if( isset( $attributes['site'] ) ) {
			$fixed['site'] = $attributes['site'];
		}

		if( isset( $attributes['locale'] ) ) {
			$fixed['locale'] = $attributes['locale'];
		}

		if( isset( $attributes['currency'] ) ) {
			$fixed['currency'] = $attributes['currency'];
		}

		return $fixed;
	}


	/**
	 * Returns the closure for retrieving the user groups
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @return \Closure Function which returns the user group codes
	 */
	protected function getGroups( \Aimeos\MShop\Context\Item\Iface $context )
	{
		return function() use ( $context )
		{
			$list = array();
			$manager = \Aimeos\MShop\Factory::createManager( $context, 'customer/group' );

			$search = $manager->createSearch();
			$search->setConditions( $search->compare( '==', 'customer.group.id', $context->getGroupIds() ) );

			foreach( $manager->searchItems( $search ) as $item ) {
				$list[] = $item->getCode();
			}

			return $list;
		};
	}
}
