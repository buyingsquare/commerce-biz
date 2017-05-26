<?php

class JobsTest extends \PHPUnit\Framework\TestCase
{
	public function testRun()
	{
		$cfgfile = dirname( dirname( __DIR__ ) ) . '/src/aimeos-settings.php';
		$argv = array( "jobs.php", "--config=$cfgfile", "index/optimize", "unittest" );

		$result = \Aimeos\Slim\Command\Jobs::run( $argv );

		$this->expectOutputString( "Executing the Aimeos jobs for \"unittest\"\n" );
	}


	/**
	 * @expectedException Aimeos\Slim\Command\Exception
	 */
	public function testRunHelp()
	{
		\Aimeos\Slim\Command\Cache::run( array( "jobs.php", "--help" ) );
	}
}
