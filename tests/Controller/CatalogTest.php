<?php

class CatalogTest extends \LocalWebTestCase
{
	public function testCountAction()
	{
		$response = $this->call( 'GET', '/unittest/count' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/javascript', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '// <!--', (string) $response->getBody() );
	}


	public function testDetailAction()
	{
		$response = $this->call( 'GET', '/unittest/d/test/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'catalog-detail', (string) $response->getBody() );
	}


	public function testDetailActionPin()
	{
		$response = $this->call( 'GET', '/unittest/d/pin' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'catalog-detail', (string) $response->getBody() );
	}


	public function testListAction()
	{
		$response = $this->call( 'GET', '/unittest/list' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'catalog-list', (string) $response->getBody() );
	}


	public function testStockAction()
	{
		$response = $this->call( 'GET', '/unittest/stock' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/javascript', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '// <!--', (string) $response->getBody() );
	}


	public function testSuggestAction()
	{
		$response = $this->call( 'GET', '/unittest/suggest' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'application/json', $response->getHeader( 'Content-Type' ) );
		$this->assertStringStartsWith( '[', (string) $response->getBody() );
	}


	public function testTreeAction()
	{
		$response = $this->call( 'GET', '/unittest/c/name/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'catalog-list', (string) $response->getBody() );
	}
}
