<?php

class BasketTest extends \LocalWebTestCase
{
	public function testIndexAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/basket' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertStringContainsString( 'basket-standard', (string) $response->getBody() );
	}
}
