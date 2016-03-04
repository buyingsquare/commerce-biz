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
 * Aimeos controller for checkout related functionality.
 *
 * @package Slim
 * @subpackage Controller
 */
class Checkout
{
	/**
	 * Returns the html for the checkout confirmation page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function confirmAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$contents = $container->get( 'aimeos_page' )->getSections( 'checkout-confirm', $request, $response, $args );
		return $container->get( 'view' )->render( $response, 'Checkout/confirm.html.twig', $contents );
	}


	/**
	 * Returns the html for the standard checkout page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function indexAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$contents = $container->get( 'aimeos_page' )->getSections( 'checkout-index', $request, $response, $args );
		return $container->get( 'view' )->render( $response, 'Checkout/index.html.twig', $contents );
	}


	/**
	 * Returns the view for the order update page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function updateAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$contents = $container->get( 'aimeos_page' )->getSections( 'checkout-update', $request, $response, $args );
		return $container->get( 'view' )->render( $response, 'Checkout/update.html.twig', $contents );
	}
}