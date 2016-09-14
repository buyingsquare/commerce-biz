<?php

class I18nTest extends \PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$conf = array(
			'apc_enabled' => true,
			'i18n' => array( 'en' => array( 0 => array() ) ),
		);

		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_config'] = new \Aimeos\Slim\Base\Config( $container, $conf );


		$object = new \Aimeos\Slim\Base\I18n( $container );
		$list = $object->get( array( 'en' ) );

		$this->assertInstanceOf( '\Aimeos\MW\Translation\Iface', $list['en'] );
	}
}
