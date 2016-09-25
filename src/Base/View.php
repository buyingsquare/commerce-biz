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
	 * @param \Aimeos\MW\Config\Iface $config Config object
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $attributes Associative list of URI parameters
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param string|null $locale Code of the current language or null for no translation
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public function create( \Aimeos\MW\Config\Iface $config, ServerRequestInterface $request,
		ResponseInterface $response, array $attributes, array $templatePaths, $locale = null )
	{
		$params = $attributes + (array) $request->getParsedBody() + (array) $request->getQueryParams();

		$view = new \Aimeos\MW\View\Standard( $templatePaths );

		$this->addAccess( $view );
		$this->addConfig( $view, $config );
		$this->addCsrf( $view, $request );
		$this->addNumber( $view, $config );
		$this->addParam( $view, $params );
		$this->addRequest( $view, $request );
		$this->addResponse( $view, $response );
		$this->addTranslate( $view, $locale );
		$this->addUrl( $view, $attributes );

		return $view;
	}


	/**
	 * Adds the "access" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addAccess( \Aimeos\MW\View\Iface $view )
	{
		$helper = new \Aimeos\MW\View\Helper\Access\All( $view );
		$view->addHelper( 'access', $helper );

		return $view;
	}


	/**
	 * Adds the "config" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Config\Iface $config Configuration object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addConfig( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config )
	{
		$config = new \Aimeos\MW\Config\Decorator\Protect( clone $config, array( 'admin', 'client' ) );
		$helper = new \Aimeos\MW\View\Helper\Config\Standard( $view, $config );
		$view->addHelper( 'config', $helper );

		return $view;
	}


	/**
	 * Adds the "access" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param ServerRequestInterface $request Request object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addCsrf( \Aimeos\MW\View\Iface $view, ServerRequestInterface $request )
	{
		$name = $request->getAttribute( 'csrf_name' );
		$value = $request->getAttribute( 'csrf_value' );

		$helper = new \Aimeos\MW\View\Helper\Csrf\Standard( $view, $name, $value );
		$view->addHelper( 'csrf', $helper );

		return $view;
	}


	/**
	 * Adds the "number" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Config\Iface $config Configuration object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addNumber( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config )
	{
		$sepDec = $config->get( 'client/html/common/format/seperatorDecimal', '.' );
		$sep1000 = $config->get( 'client/html/common/format/seperator1000', ' ' );
		$decimals = $config->get( 'client/html/common/format/decimals', 2 );

		$helper = new \Aimeos\MW\View\Helper\Number\Standard( $view, $sepDec, $sep1000, $decimals );
		$view->addHelper( 'number', $helper );

		return $view;
	}


	/**
	 * Adds the "param" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param array $attributes Associative list of request parameters
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addParam( \Aimeos\MW\View\Iface $view, array $params )
	{
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, $params );
		$view->addHelper( 'param', $helper );

		return $view;
	}


	/**
	 * Adds the "request" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param ServerRequestInterface $request Request object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addRequest( \Aimeos\MW\View\Iface $view, ServerRequestInterface $request )
	{
		$helper = new \Aimeos\MW\View\Helper\Request\Slim( $view, $request );
		$view->addHelper( 'request', $helper );

		return $view;
	}


	/**
	 * Adds the "response" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param ResponseInterface $response Response object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected static function addResponse( \Aimeos\MW\View\Iface $view, ResponseInterface $response )
	{
		$helper = new \Aimeos\MW\View\Helper\Response\Slim( $view, $response );
		$view->addHelper( 'response', $helper );

		return $view;
	}


	/**
	 * Adds the "translate" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param string|null $locale ISO language code, e.g. "de" or "de_CH"
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addTranslate( \Aimeos\MW\View\Iface $view, $locale )
	{
		if( $locale !== null )
		{
			$i18n = $this->container->get( 'aimeos_i18n' )->get( array( $locale ) );
			$translation = $i18n[$locale];
		}
		else
		{
			$translation = new \Aimeos\MW\Translation\None( 'en' );
		}

		$helper = new \Aimeos\MW\View\Helper\Translate\Standard( $view, $translation );
		$view->addHelper( 'translate', $helper );

		return $view;
	}


	/**
	 * Adds the "url" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param array $attributes Associative list of URI parameters
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addUrl( \Aimeos\MW\View\Iface $view, array $attributes )
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

		$helper = new \Aimeos\MW\View\Helper\Url\Slim( $view, $this->container->get( 'router' ), $fixed );
		$view->addHelper( 'url', $helper );

		return $view;
	}
}
