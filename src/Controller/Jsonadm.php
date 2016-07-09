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
		$status = 500;
		$header = $request->getHeaders();

		$client = self::createClient( $container, $request, $response, $args );
		$result = $client->delete( (string) $request->getBody(), $header, $status );

		return self::withResponse( $response, $result, $status, $header );
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
		$status = 500;
		$header = $request->getHeaders();

		$client = self::createClient( $container, $request, $response, $args );
		$result = $client->get( (string) $request->getBody(), $header, $status );

		return self::withResponse( $response, $result, $status, $header );
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
		$status = 500;
		$header = $request->getHeaders();

		$client = self::createClient( $container, $request, $response, $args );
		$result = $client->patch( (string) $request->getBody(), $header, $status );

		return self::withResponse( $response, $result, $status, $header );
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
		$status = 500;
		$header = $request->getHeaders();

		$client = self::createClient( $container, $request, $response, $args );
		$result = $client->post( (string) $request->getBody(), $header, $status );

		return self::withResponse( $response, $result, $status, $header );
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
		$status = 500;
		$header = $request->getHeaders();

		$client = self::createClient( $container, $request, $response, $args );
		$result = $client->put( (string) $request->getBody(), $header, $status );

		return self::withResponse( $response, $result, $status, $header );
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
		$status = 500;
		$header = $request->getHeaders();

		$client = self::createClient( $container, $request, $response, $args );
		$result = $client->options( (string) $request->getBody(), $header, $status );

		return self::withResponse( $response, $result, $status, $header );
	}


	/**
	 * Returns the resource controller
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return \Aimeos\Controller\JsonAdm\Iface JSON admin controller
	 */
	protected static function createClient( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$resource = ( isset( $args['resource'] ) ? $args['resource'] : null );
		$site = ( isset( $args['site'] ) ? $args['site'] : 'default' );
		$lang = ( isset( $args['lang'] ) ? $args['lang'] : 'en' );

		$templatePaths = $container->get( 'aimeos' )->getCustomPaths( 'admin/jsonadm/templates' );

		$context = $container->get( 'aimeos_context' )->get( false, $args );
		$context = self::setLocale( $container->get( 'aimeos_i18n' ), $context, $site, $lang );

		$view = $container->get( 'aimeos_view' )->create( $context, $request, $response, $args, $templatePaths, $lang );
		$context->setView( $view );

		return \Aimeos\Admin\JsonAdm\Factory::createClient( $context, $templatePaths, $resource );
	}


	/**
	 * Populates the response object
	 *
	 * @param ResponseInterface $response Response object
	 * @param string $content Body of the HTTP response
	 * @param integer $status HTTP status
	 * @param array $header List of HTTP headers
	 * @return ResponseInterface $response Populated response object
	 */
	protected static function withResponse( ResponseInterface $response, $content, $status, array $header )
	{
		$response->getBody()->write( $content );
		$response = $response->withStatus( $status );

		foreach( $header as $key => $value ) {
			$response->withHeader( $key, $value );
		}

		return $response;
	}


	/**
	 * Sets the locale item in the given context
	 *
	 * @param \Aimeos\Slim\Base\I18n $i18n Aimeos translation object builder
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @param string $lang ISO language code, e.g. "en" or "en_GB"
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function setLocale( \Aimeos\Slim\Base\I18n $i18n, \Aimeos\MShop\Context\Item\Iface $context, $sitecode, $lang )
	{
		$localeManager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

		try
		{
			$localeItem = $localeManager->bootstrap( $sitecode, '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );
		}
		catch( \Aimeos\MShop\Locale\Exception $e )
		{
			$localeItem = $localeManager->createItem();
		}

		$context->setLocale( $localeItem );
		$context->setI18n( $i18n->get( array( $lang ) ) );

		return $context;
	}
}
