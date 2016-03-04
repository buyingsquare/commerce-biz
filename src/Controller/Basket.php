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
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function indexAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$contents = $container->get( 'aimeos_page' )->getSections( 'basket-index' );
		return $container->get( 'view' )->render( $response, 'Basket/index.html.twig', $contents );
	}
}