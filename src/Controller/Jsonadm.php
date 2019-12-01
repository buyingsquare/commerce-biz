<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Slim
 * @subpackage Controller
 */


namespace Aimeos\Slim\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Aimeos controller for the JSON REST API
 *
 * @package Slim
 * @subpackage Controller
 */
class Jsonadm
{
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
		return self::createAdmin( $container, $request, $response, $args )->delete( $request, $response );
	}


	/**
	 * Returns the requested resource object or list of resource objects
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function getAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		return self::createAdmin( $container, $request, $response, $args )->get( $request, $response );
	}


	/**
	 * Updates a resource object or a list of resource objects
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function patchAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		return self::createAdmin( $container, $request, $response, $args )->patch( $request, $response );
	}


	/**
	 * Creates a new resource object or a list of resource objects
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function postAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		return self::createAdmin( $container, $request, $response, $args )->post( $request, $response );
	}


	/**
	 * Creates or updates a single resource object
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function putAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		return self::createAdmin( $container, $request, $response, $args )->put( $request, $response );
	}


	/**
	 * Returns the available HTTP verbs and the resource URLs
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function optionsAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		return self::createAdmin( $container, $request, $response, $args )->options( $request, $response );
	}


	/**
	 * Returns the resource controller
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return \Aimeos\Admin\JsonAdm\Iface JSON admin client
	 */
	protected static function createAdmin( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$resource = ( isset( $args['resource'] ) ? $args['resource'] : null );
		$site = ( isset( $args['site'] ) ? $args['site'] : 'default' );
		$lang = ( isset( $args['lang'] ) ? $args['lang'] : 'en' );

		$aimeos = $container->get( 'aimeos' );
		$templatePaths = $aimeos->getCustomPaths( 'admin/jsonadm/templates' );

		$context = $container->get( 'aimeos.context' )->get( false, $args, 'backend' );
		$context->setI18n( $container->get( 'aimeos.i18n' )->get( array( $lang, 'en' ) ) );
		$context->setLocale( $container->get( 'aimeos.locale' )->getBackend( $context, $site ) );

		$view = $container->get( 'aimeos.view' )->create( $context, $request, $response, $args, $templatePaths, $lang );
		$context->setView( $view );

		return \Aimeos\Admin\JsonAdm::create( $context, $aimeos, $resource );
	}
}
