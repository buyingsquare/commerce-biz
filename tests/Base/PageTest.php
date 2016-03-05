<?php

class PageTest extends \PHPUnit_Framework_TestCase
{
	public function testGetSections()
	{
		$app = new \Slim\App( array() );
		$basedir = dirname( dirname( __DIR__ ) );
		$settings = require $basedir . '/src/settings.php';
		$settings['page']['test'] = array( 'catalog/filter', 'basket/mini' );

		$boot = new \Aimeos\Slim\Bootstrap( $app, $settings );
		$boot->setup( $basedir . '/ext' )->routes( $basedir . '/src/routes.php' );

		$response = new \Slim\Http\Response();
		$request = \Slim\Http\Request::createFromEnvironment( \Slim\Http\Environment::mock() );

		$object = new \Aimeos\Slim\Base\Page( $app->getContainer() );
		$result = $object->getSections( 'test', $request, $response, array( 'site' => 'unittest' ) );

		$this->assertArrayHasKey( 'aiheader', $result );
		$this->assertArrayHasKey( 'aibody', $result );
		$this->assertArrayHasKey( 'catalog/filter', $result['aibody'] );
		$this->assertArrayHasKey( 'catalog/filter', $result['aiheader'] );
		$this->assertArrayHasKey( 'basket/mini', $result['aibody'] );
		$this->assertArrayHasKey( 'basket/mini', $result['aiheader'] );
	}
}
