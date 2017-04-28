<?php

class JobsTest extends \PHPUnit\Framework\TestCase
{
	public function testRun()
	{
		$cfgfile = dirname( dirname( __DIR__ ) ) . '/src/aimeos-settings.php';
		$argv = array( "jobs.php", "--config=$cfgfile", "index/rebuild", "unittest" );

		ob_start();
		$result = \Aimeos\Slim\Command\Jobs::run( $argv );
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( "Executing the Aimeos jobs for \"unittest\"\n", $output );
	}


	/**
	 * @expectedException Aimeos\Slim\Command\Exception
	 */
	public function testRunHelp()
	{
		$cfgfile = dirname( dirname( __DIR__ ) ) . '/src/aimeos-settings.php';
		$argv = array( "jobs.php", "--help" );

		\Aimeos\Slim\Command\Cache::run( $argv );
	}
}
