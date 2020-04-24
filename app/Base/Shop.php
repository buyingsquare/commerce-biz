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
 * Service providing the shop object
 *
 * @package Slim
 * @subpackage Base
 */
class Shop
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
	 * Returns the body and header sections created by the clients configured for the given page name.
	 *
	 * @param string $pageName Name of the configured page
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array Associative list of URI attributes
	 * @return array Associative list with body and header output separated by client name
	 */
	public function get( $pageName, ServerRequestInterface $request, ResponseInterface $response, array $attr ) : array
	{
		$tmplPaths = $this->container->get( 'aimeos' )->getCustomPaths( 'client/html/templates' );
		$context = $this->container->get( 'aimeos.context' )->get( true, $attr );
		$langid = $context->getLocale()->getLanguageId();

		$view = $this->container->get( 'aimeos.view' )->create( $context, $request, $response, $attr, $tmplPaths, $langid );
		$context->setView( $view );

		$pagesConfig = $this->container->get( 'aimeos.config' )->get()->get( 'page', array() );
		$contents = array( 'aibody' => array(), 'aiheader' => array() );

		if( isset( $pagesConfig[$pageName] ) )
		{
			foreach( (array) $pagesConfig[$pageName] as $clientName )
			{
				$client = \Aimeos\Client\Html::create( $context, $clientName );
				$client->setView( clone $view );
				$client->process();

                $contents['aibody'][$clientName] = $client->getBody();
                $contents['aiheader'][$clientName] = $client->getHeader();
			}
		}

        return array('langid' => $langid, 'contents' => $contents);
	}
}
