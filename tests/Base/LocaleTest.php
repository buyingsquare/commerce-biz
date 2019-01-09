<?php

class LocaleTest extends \PHPUnit\Framework\TestCase
{
	public function testGet()
	{
		$settings = require dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$settings['disableSites'] = false;

		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos.context'] = new \Aimeos\Slim\Base\Context( $container );
		$container['aimeos.config'] = new \Aimeos\Slim\Base\Config( $container, $settings );
		$container['mailer'] = new \Swift_Mailer( new \Swift_SendmailTransport() );

		$context = $container['aimeos.context']->get( false, array(), 'backend' );
		$object = new \Aimeos\Slim\Base\Locale( $container );

		$this->assertInstanceOf( '\Aimeos\MShop\Locale\Item\Iface', $object->get( $context, array( 'site' => 'unittest' ) ) );
	}


	public function testGetBackend()
	{
		$settings = require dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$settings['disableSites'] = false;

		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos.context'] = new \Aimeos\Slim\Base\Context( $container );
		$container['aimeos.config'] = new \Aimeos\Slim\Base\Config( $container, $settings );
		$container['mailer'] = new \Swift_Mailer( new \Swift_SendmailTransport() );

		$context = $container['aimeos.context']->get( false, array(), 'backend' );
		$object = new \Aimeos\Slim\Base\Locale( $container );

		$this->assertInstanceOf( '\Aimeos\MShop\Locale\Item\Iface', $object->getBackend( $context, 'unittest' ) );
	}
}
