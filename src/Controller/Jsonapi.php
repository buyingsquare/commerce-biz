<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 * @package Slim
 * @subpackage Controller
 */


namespace Aimeos\Slim\Controller;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Aimeos controller for the frontend JSON REST API
 *
 * @package Slim
 * @subpackage Controller
 */
class Jsonapi
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
		return self::createClient( $container, $request, $response, $args )->delete( $request, $response );
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
		return self::createClient( $container, $request, $response, $args )->get( $request, $response );
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
		return self::createClient( $container, $request, $response, $args )->patch( $request, $response );
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
		return self::createClient( $container, $request, $response, $args )->post( $request, $response );
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
		return self::createClient( $container, $request, $response, $args )->put( $request, $response );
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
		return self::createClient( $container, $request, $response, $args )->options( $request, $response );
	}


	/**
	 * Returns the resource controller
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return \Aimeos\Client\JsonApi\Iface JSON client controller
	 */
	protected static function createClient( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$params = $request->getQueryParams();
		$resource = ( isset( $args['resource'] ) ? $args['resource'] : ( isset( $params['resource'] ) ? $params['resource'] : null ) );
		$related = ( isset( $args['related'] ) ? $args['related'] : ( isset( $params['related'] ) ? $params['related'] : null ) );
		$tmplPaths = $container->get( 'aimeos' )->getCustomPaths( 'client/jsonapi/templates' );

		$context = $container->get( 'aimeos.context' )->get( true, $args );
		$langid = $context->getLocale()->getLanguageId();

		$view = $container->get( 'aimeos.view' )->create( $context, $request, $response, $args, $tmplPaths, $langid );
		$context->setView( $view );

		return \Aimeos\Client\JsonApi\Factory::createClient( $context, $resource . '/' . $related );
	}
}
