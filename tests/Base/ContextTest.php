<?php

class ContextTest extends \PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$settings = require dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$settings['disableSites'] = false;

		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_i18n'] = new \Aimeos\Slim\Base\I18n( $container );
		$container['aimeos_locale'] = new \Aimeos\Slim\Base\Locale( $container );
		$container['aimeos_config'] = new \Aimeos\Slim\Base\Config( $container, $settings );
		$container['mailer'] = new \Swift_Mailer( new \Swift_SendmailTransport() );

		$object = new \Aimeos\Slim\Base\Context( $container );

		$this->assertInstanceOf( '\Aimeos\MShop\Context\Item\Iface', $object->get( true, array( 'site' => 'unittest' ) ) );
	}
}
