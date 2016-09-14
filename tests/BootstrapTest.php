<?php

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
	public function testSetup()
	{
		$app = new \Slim\App();
		$c = $app->getContainer();

		$boot = new \Aimeos\Slim\Bootstrap( $app, array( 'apc_enabled' => true ) );
		$boot->setup( '.' );

		$this->assertInstanceOf( '\Aimeos\Bootstrap', $c['aimeos'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Config', $c['aimeos_config'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Context', $c['aimeos_context'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\I18n', $c['aimeos_i18n'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Page', $c['aimeos_page'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\View', $c['aimeos_view'] );
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
		$result = \Aimeos\Slim\Bootstrap::getVersion();

		$this->assertInternalType( 'string', $result );
	}
}
