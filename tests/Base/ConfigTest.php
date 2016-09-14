<?php

class ConfigTest extends \PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$settings = require dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$settings['frontend']['test'] = 1;
		$settings['backend']['test'] = 0;

		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();


		$object = new \Aimeos\Slim\Base\Config( $container, $settings );
		$config = $object->get( 'frontend' );

		$this->assertInstanceOf( '\Aimeos\MW\Config\Iface', $config );
		$this->assertEquals( 1, $config->get( 'frontend/test', 0 ) );
		$this->assertEquals( 0, $config->get( 'backend/test', 1 ) );
	}
}
