<?php

class ContextTest extends \PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$settings = require dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';

		$router = new \Slim\Router();
		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_i18n'] = new \Aimeos\Slim\Base\I18n( $container );
		$container['aimeos_config'] = new \Aimeos\MW\Config\PHPArray( $settings, $container['aimeos']->getConfigPaths() );
		$container['mailer'] = \Swift_Mailer::newInstance( \Swift_SendmailTransport::newInstance() );


		$object = new \Aimeos\Slim\Base\Context( $container );
		$this->assertInstanceOf( '\Aimeos\MShop\Context\Item\Iface', $object->get( true, array( 'site' => 'unittest' ) ) );
	}
}
