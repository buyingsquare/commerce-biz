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
 * Aimeos controller for basket related functionality.
 *
 * @package Slim
 * @subpackage Controller
 */
class Basket
{
	/**
	 * Returns the html for the standard basket page.
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function indexAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$contents = $container->get( 'shop' )->get( 'basket-index', $request, $response, $args );
		$response = $container->get( 'view' )->render( $response, 'Basket/index.html.twig', $contents );

		return $response->withHeader( 'Cache-Control', 'no-store' );
	}
}