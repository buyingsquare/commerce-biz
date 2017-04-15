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
 * Aimeos controller for ExtJS admin interface
 *
 * @package Slim
 * @subpackage Controller
 */
class Extadm
{
	/**
	 * Returns the view for the ExtJS admin interface
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function indexAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$site = ( isset( $args['site'] ) ? $args['site'] : 'default' );
		$lang = ( isset( $args['lang'] ) ? $args['lang'] : 'en' );
		$tab = ( isset( $args['tab'] ) ? $args['tab'] : 0 );

		$aimeos = $container->get( 'aimeos' );
		$cntlPaths = $aimeos->getCustomPaths( 'controller/extjs' );

		$context = $container->get( 'aimeos_context' )->get( false, $args, 'backend' );
		$context = self::setLocale( $context, $site, $lang );

		$controller = new \Aimeos\Controller\ExtJS\JsonRpc( $context, $cntlPaths );
		$cssFiles = array();

		foreach( $aimeos->getCustomPaths( 'admin/extjs' ) as $base => $paths )
		{
			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;

				if( !is_file( $jsbAbsPath ) ) {
					throw new \Exception( sprintf( 'JSB2 file "%1$s" not found', $jsbAbsPath ) );
				}

				$jsb2 = new \Aimeos\MW\Jsb2\Standard( $jsbAbsPath, dirname( $path ) );
				$cssFiles = array_merge( $cssFiles, $jsb2->getUrls( 'css' ) );
			}
		}

		$csrfname = $request->getAttribute( 'csrf_name' );
		$csrfvalue = $request->getAttribute( 'csrf_value' );

		$router = $container->get( 'router' );
		$jsonUrl = $router->pathFor( 'aimeos_shop_extadm_json', array( 'site' => $site, $csrfname => $csrfvalue ) );
		$jqadmUrl = $router->pathFor( 'aimeos_shop_jqadm_search', array( 'site' => $site, 'lang' => $lang, 'resource' => 'dashboard' ) );
		$adminUrl = $router->pathFor( 'aimeos_shop_extadm', array( 'site' => '<site>', 'lang' => '<lang>', 'tab' => '<tab>' ) );

		$vars = array(
			'lang' => $lang,
			'cssFiles' => $cssFiles,
			'languages' => self::getJsonLanguages( $aimeos ),
			'config' => self::getJsonClientConfig( $context ),
			'site' => self::getJsonSiteItem( $context, $site ),
			'i18nContent' => self::getJsonClientI18n( $aimeos->getI18nPaths(), $lang ),
			'uploaddir' => $context->getConfig()->get( 'uploaddir', '/' ),
			'searchSchemas' => $controller->getJsonSearchSchemas(),
			'itemSchemas' => $controller->getJsonItemSchemas(),
			'smd' => $controller->getJsonSmd( $jsonUrl ),
			'urlTemplate' => str_replace( ['<', '>'], ['{', '}'], urldecode( $adminUrl ) ),
			'jqadmurl' => $jqadmUrl,
			'activeTab' => $tab,
			'version' => \Aimeos\Slim\Bootstrap::getVersion(),
			'extensions' => implode( ',', $aimeos->getExtensions() ),
		);

		return $container->get( 'view' )->render( $response, 'Extadm/index.html.twig', $vars );
	}


	/**
	 * Single entry point for all JSON admin requests
	 *
	 * @param ContainerInterface $container Dependency injection container
	 * @param ServerRequestInterface $request Request object
	 * @param ResponseInterface $response Response object
	 * @param array $args Associative list of route parameters
	 * @return ResponseInterface $response Modified response object with generated output
	 */
	public static function doAction( ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response, array $args )
	{
		$cntlPaths = $container->get( 'aimeos' )->getCustomPaths( 'controller/extjs' );

		$context = $container->get( 'aimeos_context' )->get( false, $args, 'backend' );
		$context->setView( $container->get( 'aimeos_view' )->create( $context->getConfig(), $request, $response, $args, array() ) );
		$context = self::setLocale( $context );

		$params = $request->getQueryParams();
		if( ( $post = $request->getParsedBody() ) !== null ) {
			$params = array_merge( $params, (array) $post );
		}

		$controller = new \Aimeos\Controller\ExtJS\JsonRpc( $context, $cntlPaths );
		$output = $controller->process( $params, (string) $request->getBody() );
		$response->getBody()->write( $output );

		return $response;
	}


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
		$jsFiles = array();
		$aimeos = $container->get( 'aimeos' );

		foreach( $aimeos->getCustomPaths( 'admin/extjs' ) as $base => $paths )
		{
			foreach( $paths as $path )
			{
				$jsbAbsPath = $base . '/' . $path;
				$jsb2 = new \Aimeos\MW\Jsb2\Standard( $jsbAbsPath, dirname( $jsbAbsPath ) );
				$jsFiles = array_merge( $jsFiles, $jsb2->getFiles( 'js' ) );
			}
		}

		foreach( $jsFiles as $file )
		{
			if( ( $content = file_get_contents( $file ) ) !== false ) {
				$contents .= $content;
			}
		}

		return $response->withHeader( 'Content-Type', 'application/javascript' )->getBody()->write( $contents );
	}


	/**
	 * Creates a list of all available translations
	 *
	 * @param \Aimeos\Bootstrap $aimeos Aimeos object
	 * @return array List of language IDs with labels
	 */
	protected static function getJsonLanguages( \Aimeos\Bootstrap $aimeos )
	{
		$result = array();

		foreach( $aimeos->getI18nList( 'admin' ) as $id ) {
			$result[] = array( 'id' => $id, 'label' => $id );
		}

		return json_encode( $result );
	}


	/**
	 * Returns the JSON encoded configuration for the ExtJS client.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context item object
	 * @return string JSON encoded configuration object
	 */
	protected static function getJsonClientConfig( \Aimeos\MShop\Context\Item\Iface $context )
	{
		$config = $context->getConfig()->get( 'admin/extjs', array() );
		return json_encode( array( 'admin' => array( 'extjs' => $config ) ), JSON_FORCE_OBJECT );
	}


	/**
	 * Returns the JSON encoded translations for the ExtJS client.
	 *
	 * @param array $i18nPaths List of file system paths which contain the translation files
	 * @param string $lang ISO language code like "en" or "en_GB"
	 * @return string JSON encoded translation object
	 */
	protected static function getJsonClientI18n( array $i18nPaths, $lang )
	{
		$i18n = new \Aimeos\MW\Translation\Gettext( $i18nPaths, $lang );

		$content = array(
			'admin' => $i18n->getAll( 'admin' ),
			'admin/ext' => $i18n->getAll( 'admin/ext' ),
		);

		return json_encode( $content, JSON_FORCE_OBJECT );
	}


	/**
	 * Returns the JSON encoded site item.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context item object
	 * @param string $site Unique site code
	 * @return string JSON encoded site item object
	 * @throws Exception If no site item was found for the code
	 */
	protected static function getJsonSiteItem( \Aimeos\MShop\Context\Item\Iface $context, $site )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'locale/site' );

		$criteria = $manager->createSearch();
		$criteria->setConditions( $criteria->compare( '==', 'locale.site.code', $site ) );
		$items = $manager->searchItems( $criteria );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \Exception( sprintf( 'No site found for code "%1$s"', $site ) );
		}

		return json_encode( $item->toArray() );
	}


	/**
	 * Sets the locale item in the given context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @param string $locale ISO language code, e.g. "en" or "en_GB"
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected static function setLocale( \Aimeos\MShop\Context\Item\Iface $context, $sitecode = 'default', $locale = null )
	{
		$localeManager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

		try {
			$localeItem = $localeManager->bootstrap( $sitecode, $locale, '', false );
		} catch( \Aimeos\MShop\Locale\Exception $e ) {
			$localeItem = $localeManager->createItem();
		}

		$context->setLocale( $localeItem );

		return $context;
	}
}
