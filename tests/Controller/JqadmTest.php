<?php

class JqadmControllerTest extends \LocalWebTestCase
{
	public function testFileActionCss()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/file/css' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( '.aimeos', (string) $response->getBody() );
	}


	public function testFileActionJs()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/file/js' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'Aimeos = {', (string) $response->getBody() );
	}


	public function testCopyAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/copy/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'item-product', (string) $response->getBody() );
	}


	public function testCreateAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/create/product' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'item-product', (string) $response->getBody() );
	}


	public function testDeleteAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/delete/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'list-items', (string) $response->getBody() );
	}


	public function testGetAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/get/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'item-product', (string) $response->getBody() );
	}


	public function testSaveAction()
	{
		$response = $this->call( 'POST', '/unittest/admin/jqadm/save/product/0' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'item-product', (string) $response->getBody() );
	}


	public function testSearchAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/jqadm/search/product' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'list-items', (string) $response->getBody() );
	}
}
