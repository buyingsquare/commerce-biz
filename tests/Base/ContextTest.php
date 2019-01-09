<?php

class ContextTest extends \PHPUnit\Framework\TestCase
{
	public function testGet()
	{
		$settings = require dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$settings['disableSites'] = false;

		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos.i18n'] = new \Aimeos\Slim\Base\I18n( $container );
		$container['aimeos.locale'] = new \Aimeos\Slim\Base\Locale( $container );
		$container['aimeos.config'] = new \Aimeos\Slim\Base\Config( $container, $settings );
		$container['mailer'] = new \Swift_Mailer( new \Swift_SendmailTransport() );

		$object = new \Aimeos\Slim\Base\Context( $container );

		$this->assertInstanceOf( '\Aimeos\MShop\Context\Item\Iface', $object->get( true, array( 'site' => 'unittest' ) ) );
	}
}
