<?php

class CatalogTest extends \LocalWebTestCase
{
	public function testCountAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/count' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/javascript', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '// <!--', (string) $response->getBody() );
	}


	public function testDetailAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/test/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertStringContainsString( 'catalog-detail', (string) $response->getBody() );
	}


	public function testDetailActionPin()
	{
		$response = $this->call( 'GET', '/unittest/shop/pin' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertStringContainsString( 'catalog-detail', (string) $response->getBody() );
	}


	public function testListAction()
	{
		$response = $this->call( 'GET', '/unittest/shop' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertStringContainsString( 'catalog-list', (string) $response->getBody() );
	}


	public function testStockAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/stock' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/javascript', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '// <!--', (string) $response->getBody() );
	}


	public function testSuggestAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/suggest' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/json', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '[', (string) $response->getBody() );
	}


	public function testTreeAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/name~0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertStringContainsString( 'catalog-list', (string) $response->getBody() );
	}
}
