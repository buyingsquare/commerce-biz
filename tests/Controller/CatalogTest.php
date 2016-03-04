<?php

class CatalogTest extends \LocalWebTestCase
{
	public function testCountAction()
	{
		$response = $this->call( 'GET', '/count' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/javascript', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '// <!--', (string) $response->getBody() );
	}


	public function testDetailAction()
	{
		$response = $this->call( 'GET', '/detail/1/test' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'catalog-detail', (string) $response->getBody() );
	}


	public function testListAction()
	{
		$response = $this->call( 'GET', '/list' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'catalog-list', (string) $response->getBody() );
	}


	public function testStockAction()
	{
		$response = $this->call( 'GET', '/stock' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/javascript', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '// <!--', (string) $response->getBody() );
	}


	public function testSuggestAction()
	{
		$response = $this->call( 'GET', '/suggest' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/json', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '[', (string) $response->getBody() );
	}
}
