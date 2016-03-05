<?php

class AccountTest extends \LocalWebTestCase
{
	public function testIndexAction()
	{
		$response = $this->call( 'GET', '/unittest/myaccount' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'account-history', (string) $response->getBody() );
	}


	public function testIndexActionFavorite()
	{
		$response = $this->call( 'GET', '/unittest/myaccount/favorite' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'account-history', (string) $response->getBody() );
	}


	public function testIndexActionWatch()
	{
		$response = $this->call( 'GET', '/unittest/myaccount/watch' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'account-history', (string) $response->getBody() );
	}


	public function testDownloadAction()
	{
		$response = $this->call( 'GET', '/unittest/myaccount/download/0' );

		$this->assertEquals( 401, $response->getStatusCode() );
	}
}
