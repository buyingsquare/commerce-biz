<?php

class AccountTest extends \LocalWebTestCase
{
	public function testIndexAction()
	{
		$response = $this->call( 'GET', '/myaccount' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'account-history', (string) $response->getBody() );
	}


	public function testDownloadAction()
	{
		$response = $this->call( 'GET', '/myaccount/download/0' );

		$this->assertEquals( 401, $response->getStatusCode() );
	}
}
