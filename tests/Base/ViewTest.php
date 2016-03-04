<?php

class ViewTest extends \PHPUnit_Framework_TestCase
{
	public function testGet()
	{
		$router = new \Slim\Router();
		$container = new \Slim\Container();
		$container['aimeos'] = new \Aimeos\Bootstrap();
		$container['aimeos_config'] = new \Aimeos\MW\Config\PHPArray();
		$container['aimeos_i18n'] = new \Aimeos\Slim\Base\I18n( $container );
		$container['request'] = \Slim\Http\Request::createFromEnvironment( \Slim\Http\Environment::mock() );
		$container['response'] = new \Slim\Http\Response();


		$object = new \Aimeos\Slim\Base\View( $container );
		$view = $object->create( $container['request'], $container['response'], array(), array(), 'en' );

		$this->assertInstanceOf( '\Aimeos\MW\View\Iface', $view );
	}
}
