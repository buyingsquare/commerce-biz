<?php

class PageTest extends \PHPUnit_Framework_TestCase
{
	public function testGetSections()
	{
		$app = new \Slim\App( array() );
		$basedir = dirname( dirname( __DIR__ ) );
		$settings = require $basedir . '/src/settings.php';
		$config = array( 'page' => array( 'test' => array( 'catalog/filter', 'basket/mini' ) ) );

		$boot = new \Aimeos\Slim\Bootstrap( $app, $settings );
		$boot->setup( $basedir . '/ext' )->routes( $basedir . '/src/routes.php' );

		$c = $app->getContainer();
		$c['request'] = \Slim\Http\Request::createFromEnvironment( \Slim\Http\Environment::mock() );
		$c['aimeos_config'] = new \Aimeos\MW\Config\PHPArray( $config, array() );


		$object = new \Aimeos\Slim\Base\Page( $app->getContainer() );
		$result = $object->getSections( 'test' );

		$this->assertArrayHasKey( 'aiheader', $result );
		$this->assertArrayHasKey( 'aibody', $result );
		$this->assertArrayHasKey( 'catalog/filter', $result['aibody'] );
		$this->assertArrayHasKey( 'catalog/filter', $result['aiheader'] );
		$this->assertArrayHasKey( 'basket/mini', $result['aibody'] );
		$this->assertArrayHasKey( 'basket/mini', $result['aiheader'] );
	}
}
