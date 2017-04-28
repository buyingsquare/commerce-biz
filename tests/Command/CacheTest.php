<?php

class CacheTest extends \PHPUnit\Framework\TestCase
{
	public function testRun()
	{
		$cfgfile = dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$argv = array( "cache.php", "--config=$cfgfile", "unittest" );

		ob_start();
		$result = \Aimeos\Slim\Command\Cache::run( $argv );
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( "Clearing the Aimeos cache for site \"unittest\"\n", $output );
	}


	public function testRunHelp()
	{
		$cfgfile = dirname( dirname( __DIR__ ) ) . '/src/aimeos-default.php';
		$argv = array( "cache.php", "--help" );

		$this->expectException( 'Aimeos\Slim\Command\Exception' );
		\Aimeos\Slim\Command\Cache::run( $argv );
	}
}
