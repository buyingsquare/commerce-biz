<?php

class BootstrapTest extends \PHPUnit\Framework\TestCase
{
	public function testSetup()
	{
		$app = new \Slim\App();
		$c = $app->getContainer();

		$boot = new \Aimeos\Slim\Bootstrap( $app, array( 'apc_enabled' => true ) );
		$boot->setup( '.' );

		$this->assertInstanceOf( '\Aimeos\Bootstrap', $c['aimeos'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Config', $c['aimeos.config'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Context', $c['aimeos.context'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Locale', $c['aimeos.locale'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\I18n', $c['aimeos.i18n'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\View', $c['aimeos.view'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Shop', $c['shop'] );
		$this->assertInstanceOf( '\Swift_Mailer', $c['mailer'] );
	}


	public function testRoutes()
	{
		$app = new \Slim\App();

		$boot = new \Aimeos\Slim\Bootstrap( $app, array() );
		$result = $boot->routes( dirname( __DIR__ ) . '/src/aimeos-routes.php' );

		$this->assertInstanceOf( '\Aimeos\Slim\Bootstrap', $result );
	}


	public function testGetVersion()
	{
		$this->assertIsString( \Aimeos\Slim\Bootstrap::getVersion() );
	}
}
