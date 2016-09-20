<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Controller
 */


namespace Aimeos\Slim\Controller;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Aimeos controller for the JQuery admin interface
 *
 * @package Slim
 * @subpackage Controller
 */
class Jqadm
{
	/**
	 * Returns the JS file content
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function fileAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$contents = '';
		$files = array();
		$aimeos = $container->get( 'aimeos' );
		$type = ( isset( $args['type'] ) ? $args['type'] : 'js' );

		foreach( $aimeos->getCustomPaths( 'admin/jqadm' ) as $base => $paths )
		{
			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;
				$jsb2 = new \Aimeos\MW\Jsb2\Standard( $jsbAbsPath, dirname( $jsbAbsPath ) );
				$files = array_merge( $files, $jsb2->getFiles( $type ) );
			}
		}

		foreach( $files as $file )
		{
			if( ( $content = file_get_contents( $file ) ) !== false ) {
				$contents .= $content;
			}
		}

		$response->getBody()->write( $contents );

		if( $type === 'js' ) {
			$response = $response->withHeader( 'Content-Type', 'application/javascript' );
		} elseif( $type === 'css' ) {
			$response = $response->withHeader( 'Content-Type', 'text/css' );
		}

		return $response;
	}


	/**
	 * Returns the HTML code for a copy of a resource object
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function copyAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntl = self::createClient( $container, $request, $response, $args );
		return self::getHtml( $container, $response, $cntl->copy() );
	}


	/**
	 * Returns the HTML code for a new resource object
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function createAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntl = self::createClient( $container, $request, $response, $args );
		return self::getHtml( $container, $response, $cntl->create() );
	}


	/**
	 * Deletes the resource object or a list of resource objects
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function deleteAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntl = self::createClient( $container, $request, $response, $args );
		return self::getHtml( $container, $response, $cntl->delete() . $cntl->search() );
	}


	/**
	 * Returns the HTML code for the requested resource object
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function getAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntl = self::createClient( $container, $request, $response, $args );
		return self::getHtml( $container, $response, $cntl->get() );
	}


	/**
	 * Saves a new resource object
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function saveAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntl = self::createClient( $container, $request, $response, $args );
		return self::getHtml( $container, $response, ( $cntl->save() ? : $cntl->search() ) );
	}


	/**
	 * Returns the HTML code for a list of resource objects
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function searchAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntl = self::createClient( $container, $request, $response, $args );
		return self::getHtml( $container, $response, $cntl->search() );
	}


	/**
	 * Returns the resource controller
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return \Aimeos\Admin\JQAdm\Iface JQAdm client
	 */
	protected static function createClient( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$resource = ( isset( $args['resource'] ) ? $args['resource'] : null );
		$site = ( isset( $args['site'] ) ? $args['site'] : 'default' );
		$lang = ( isset( $args['lang'] ) ? $args['lang'] : 'en' );

		$templatePaths = $container->get( 'aimeos' )->getCustomPaths( 'admin/jqadm/templates' );

		$context = $container->get( 'aimeos_context' )->get( false, $args, 'backend' );
		$context->setI18n( $container->get( 'aimeos_i18n' )->get( array( $lang, 'en' ) ) );
		$context->setLocale( $container->get( 'aimeos_locale' )->getBackend( $context, $site ) );

		$view = $container->get( 'aimeos_view' )->create( $context->getConfig(), $request, $response, $args, $templatePaths, $lang );
		$context->setView( $view );

		return \Aimeos\Admin\JQAdm\Factory::createClient( $context, $templatePaths, $resource );
	}


	/**
	 * Returns the generated HTML code
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ResponseInterface $response Response object
	 * @param string $content Content from admin client
	 * @return \Illuminate\Contracts\View\View View for rendering the output
	 */
	protected static function getHtml( ContainerInterface $container, ResponseInterface $response, $content )
	{
		$version = \Aimeos\Slim\Bootstrap::getVersion();
		$content = str_replace( array( '{type}', '{version}' ), array( 'Slim', $version ), $content );

		return $container->get( 'view' )->render( $response, 'Jqadm/index.html.twig', array( 'content' => $content ) );
	}
}
