<?php

class CheckoutTest extends \LocalWebTestCase
{
	public function testConfirmAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/confirm' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'checkout-confirm', (string) $response->getBody() );
	}


	public function testIndexAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/checkout' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'checkout-standard', (string) $response->getBody() );
	}


	public function testUpdateAction()
	{
		$response = $this->call( 'GET', '/unittest/update' );

		$this->assertEquals( 200, $response->getStatusCode() );
	}
}
