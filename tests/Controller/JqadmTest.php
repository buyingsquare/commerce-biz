<?php

class JqadmControllerTest extends \LocalWebTestCase
{
	public function testCopyAction()
	{
		$response = $this->call( 'GET', '/unittest/jqadm/copy/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<div class="product-item', (string) $response->getBody() );
	}


	public function testCreateAction()
	{
		$response = $this->call( 'GET', '/unittest/jqadm/create/product' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<div class="product-item', (string) $response->getBody() );
	}


	public function testDeleteAction()
	{
		$response = $this->call( 'GET', '/unittest/jqadm/delete/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<table class="list-items', (string) $response->getBody() );
	}


	public function testGetAction()
	{
		$response = $this->call( 'GET', '/unittest/jqadm/get/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<div class="product-item', (string) $response->getBody() );
	}


	public function testSaveAction()
	{
		$response = $this->call( 'POST', '/unittest/jqadm/save/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<div class="product-item', (string) $response->getBody() );
	}


	public function testSearchAction()
	{
		$response = $this->call( 'GET', '/unittest/jqadm/search/product' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<table class="list-items', (string) $response->getBody() );
	}


	public function testSearchActionSite()
	{
		$response = $this->call( 'GET', '/invalid/jqadm/search/product' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '<table class="list-items', (string) $response->getBody() );
	}
}
