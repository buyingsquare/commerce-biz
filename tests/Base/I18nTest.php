<?php

class I18nTest extends \PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_config'] = new \Aimeos\MW\Config\PHPArray();


		$object = new \Aimeos\Slim\Base\I18n( $container );
		$list = $object->get( array( 'en' ) );

		$this->assertInstanceOf( '\Aimeos\MW\Translation\Iface', $list['en'] );
	}
}
