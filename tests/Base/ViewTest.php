<?php

class ViewTest extends \PHPUnit\Framework\TestCase
{
	public function testGet()
	{
		$router = new \Slim\Router();
		$container = new \Slim\Container();
		$container['mailer'] = new \Swift_SendmailTransport();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_config'] = new \Aimeos\Slim\Base\Config( $container, array() );
		$container['aimeos_context'] = new \Aimeos\Slim\Base\Context( $container );
		$container['aimeos_i18n'] = new \Aimeos\Slim\Base\I18n( $container );
		$container['request'] = \Slim\Http\Request::createFromEnvironment( \Slim\Http\Environment::mock() );
		$container['response'] = new \Slim\Http\Response();


		$object = new \Aimeos\Slim\Base\View( $container );
		$attr = array( 'site' => 'unittest', 'locale' => 'en', 'currency' => 'EUR' );
		$view = $object->create( $container['aimeos_context']->get( false ), $container['request'], $container['response'], $attr, array(), 'en' );

		$this->assertInstanceOf( '\Aimeos\MW\View\Iface', $view );
	}


	public function testGetNoLocale()
	{
		$router = new \Slim\Router();
		$container = new \Slim\Container();
		$container['mailer'] = new \Swift_SendmailTransport();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_config'] = new \Aimeos\Slim\Base\Config( $container, array() );
		$container['aimeos_context'] = new \Aimeos\Slim\Base\Context( $container );
		$container['aimeos_i18n'] = new \Aimeos\Slim\Base\I18n( $container );
		$container['request'] = \Slim\Http\Request::createFromEnvironment( \Slim\Http\Environment::mock() );
		$container['response'] = new \Slim\Http\Response();


		$object = new \Aimeos\Slim\Base\View( $container );
		$attr = array( 'site' => 'unittest', 'locale' => 'en', 'currency' => 'EUR' );
		$view = $object->create( $container['aimeos_context']->get( false ), $container['request'], $container['response'], $attr, array() );

		$this->assertInstanceOf( '\Aimeos\MW\View\Iface', $view );
	}
}
