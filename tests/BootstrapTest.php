<?php

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
	public function testSetup()
	{
		$app = new \Slim\App();
		$c = $app->getContainer();

		$boot = new \Aimeos\Slim\Bootstrap( $app, array() );
		$boot->routes( dirname( __DIR__ ) . '/src/routes.php' )->setup( '.' );

		$this->assertInstanceOf( '\Aimeos\Bootstrap', $c['aimeos'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Context', $c['aimeos_context'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\I18n', $c['aimeos_i18n'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\Page', $c['aimeos_page'] );
		$this->assertInstanceOf( '\Aimeos\Slim\Base\View', $c['aimeos_view'] );
		$this->assertInstanceOf( '\Aimeos\MW\Config\Iface', $c['aimeos_config'] );
		$this->assertInstanceOf( '\Swift_Mailer', $c['mailer'] );
	}
}
