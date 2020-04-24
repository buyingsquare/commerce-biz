<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Base
 */

namespace Aimeos\Slim\Base;

use Psr\Container\ContainerInterface;
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
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $attributes Associative list of URI parameters
	 * @param array $templatePaths List of base path names with relative template paths as key/value pairs
	 * @param string|null $locale Code of the current language or null for no translation
	 * @return \Aimeos\MW\View\Iface View object
	 */
	public function create( \Aimeos\MShop\Context\Item\Iface $context, ServerRequestInterface $request,
		ResponseInterface $response, array $attributes, array $templatePaths, string $locale = null ) : \Aimeos\MW\View\Iface
	{
		$iface = 'Slim\Views\Twig';
		$params = $attributes + (array) $request->getParsedBody() + (array) $request->getQueryParams();

		if( isset( $this->container['view'] ) && $this->container['view'] instanceof $iface )
		{
			$twig = $this->container['view']->getEnvironment();
			$engines = array( '.html.twig' => new \Aimeos\MW\View\Engine\Twig( $twig ) );

			$view = new \Aimeos\MW\View\Standard( $templatePaths, $engines );
			$this->initTwig( $view, $twig );
		}
		else
		{
			$view = new \Aimeos\MW\View\Standard( $templatePaths );
		}

		$config = $context->getConfig();
		$session = $context->getSession();

		$this->addAccess( $view );
		$this->addConfig( $view, $config );
		$this->addCsrf( $view, $request );
		$this->addNumber( $view, $config, $locale );
		$this->addParam( $view, $params );
		$this->addRequest( $view, $request );
		$this->addResponse( $view, $response );
		$this->addSession( $view, $session );
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
	protected function addAccess( \Aimeos\MW\View\Iface $view ) : \Aimeos\MW\View\Iface
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
	protected function addConfig( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config ) : \Aimeos\MW\View\Iface
	{
		$config = new \Aimeos\MW\Config\Decorator\Protect( clone $config, ['admin', 'client', 'resource/fs/baseurl'] );
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
	protected function addCsrf( \Aimeos\MW\View\Iface $view, ServerRequestInterface $request ) : \Aimeos\MW\View\Iface
	{
		$name = $request->getAttribute( 'csrf_name' );
		$value = $request->getAttribute( 'csrf_value' );

		$helper = new \Aimeos\MW\View\Helper\Csrf\Standard( $view, (string) $name, (string) $value );
		$view->addHelper( 'csrf', $helper );

		return $view;
	}


	/**
	 * Adds the "number" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Config\Iface $config Configuration object
	 * @param string|null $locale Code of the current language or null for no translation
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addNumber( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Config\Iface $config,
		string $locale = null ) : \Aimeos\MW\View\Iface
	{
		$pattern = $config->get( 'client/html/common/format/pattern' );

		$helper = new \Aimeos\MW\View\Helper\Number\Locale( $view, $locale, $pattern );
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
	protected static function addParam( \Aimeos\MW\View\Iface $view, array $params ) : \Aimeos\MW\View\Iface
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
	protected static function addRequest( \Aimeos\MW\View\Iface $view, ServerRequestInterface $request ) : \Aimeos\MW\View\Iface
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
	protected static function addResponse( \Aimeos\MW\View\Iface $view, ResponseInterface $response ) : \Aimeos\MW\View\Iface
	{
		$helper = new \Aimeos\MW\View\Helper\Response\Slim( $view, $response );
		$view->addHelper( 'response', $helper );

		return $view;
	}


	/**
	 * Adds the "session" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Aimeos\MW\Session\Iface $session Session object
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addSession( \Aimeos\MW\View\Iface $view, \Aimeos\MW\Session\Iface $session ) : \Aimeos\MW\View\Iface
	{
		$helper = new \Aimeos\MW\View\Helper\Session\Standard( $view, $session );
		$view->addHelper( 'session', $helper );

		return $view;
	}


	/**
	 * Adds the "translate" helper to the view object
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param string|null $locale ISO language code, e.g. "de" or "de_CH"
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function addTranslate( \Aimeos\MW\View\Iface $view, string $locale = null ) : \Aimeos\MW\View\Iface
	{
		if( $locale !== null )
		{
			$i18n = $this->container->get( 'aimeos.i18n' )->get( array( $locale ) );
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
	protected function addUrl( \Aimeos\MW\View\Iface $view, array $attributes ) : \Aimeos\MW\View\Iface
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


	/**
	 * Adds the Aimeos template functions for Twig
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param \Twig_Environment $twig Twig environment object
	 */
	protected function initTwig( \Aimeos\MW\View\Iface $view, \Twig_Environment $twig )
	{
		$fcn = function( $key, $default = null ) use ( $view ) {
			return $view->config( $key, $default );
		};
		$twig->addFunction( new \Twig_SimpleFunction( 'aiconfig', $fcn ) );

		$fcn = function( $singular, array $values = array(), $domain = 'client' ) use ( $view ) {
			return vsprintf( $view->translate( $domain, $singular ), $values );
		};
		$twig->addFunction( new \Twig_SimpleFunction( 'aitrans', $fcn ) );

		$fcn = function( $singular, $plural, $number, array $values = array(), $domain = 'client' ) use ( $view ) {
			return vsprintf( $view->translate( $domain, $singular, $plural, $number ), $values );
		};
		$twig->addFunction( new \Twig_SimpleFunction( 'aitransplural', $fcn ) );
	}
}
