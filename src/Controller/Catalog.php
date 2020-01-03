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
 * Aimeos controller for catalog related functionality.
 *
 * @package Slim
 * @subpackage Controller
 */
class Catalog
{
	/**
	 * Returns the view for the XHR response with the counts for the facetted search.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function countAction( ContainerInterface $container, ServerRequestInterface $request,
		ResponseInterface $response, array $args ) : ResponseInterface
	{
		$contents = $container->get( 'shop' )->get( 'catalog-count', $request, $response, $args );
		$response = $container->get( 'view' )->render( $response, 'Catalog/count.html.twig', $contents );

		return $response->withHeader( 'Content-Type', 'application/javascript' )
			->withHeader( 'Cache-Control', 'max-age=300' );
	}


	/**
	 * Returns the html for the catalog detail page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function detailAction( ContainerInterface $container, ServerRequestInterface $request,
		ResponseInterface $response, array $args ) : ResponseInterface
	{
		$contents = $container->get( 'shop' )->get( 'catalog-detail', $request, $response, $args );
		return $container->get( 'view' )->render( $response, 'Catalog/detail.html.twig', $contents )
			->withHeader( 'Cache-Control', 'max-age=3600' );
	}


	/**
	 * Returns the html for the catalog list page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function listAction( ContainerInterface $container, ServerRequestInterface $request,
		ResponseInterface $response, array $args ) : ResponseInterface
	{
		$contents = $container->get( 'shop' )->get( 'catalog-list', $request, $response, $args );
		return $container->get( 'view' )->render( $response, 'Catalog/list.html.twig', $contents )
			->withHeader( 'Cache-Control', 'max-age=3600' );
	}


	/**
	 * Returns the html body part for the catalog stock page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function stockAction( ContainerInterface $container, ServerRequestInterface $request,
		ResponseInterface $response, array $args ) : ResponseInterface
	{
		$contents = $container->get( 'shop' )->get( 'catalog-stock', $request, $response, $args );
		$response = $container->get( 'view' )->render( $response, 'Catalog/stock.html.twig', $contents );

		return $response->withHeader( 'Cache-Control', 'max-age=30' )
			->withHeader( 'Content-Type', 'application/javascript' );
	}


	/**
	 * Returns the view for the XHR response with the product information for the search suggestion.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function suggestAction( ContainerInterface $container, ServerRequestInterface $request,
		ResponseInterface $response, array $args ) : ResponseInterface
	{
		$contents = $container->get( 'shop' )->get( 'catalog-suggest', $request, $response, $args );
		$response = $container->get( 'view' )->render( $response, 'Catalog/suggest.html.twig', $contents );

		return $response->withHeader( 'Content-Type', 'application/json' )
			->withHeader( 'Cache-Control', 'max-age=300' );
	}


	/**
	 * Returns the html for the catalog list page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function treeAction( ContainerInterface $container, ServerRequestInterface $request,
		ResponseInterface $response, array $args ) : ResponseInterface
	{
		$contents = $container->get( 'shop' )->get( 'catalog-tree', $request, $response, $args );
		return $container->get( 'view' )->render( $response, 'Catalog/tree.html.twig', $contents )
			->withHeader( 'Cache-Control', 'max-age=3600' );
	}
}