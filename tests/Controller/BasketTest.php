<?php

class BasketTest extends \LocalWebTestCase
{
	public function testIndexAction()
	{
		$response = $this->call( 'GET', '/unittest/shop/basket' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'basket-standard', (string) $response->getBody() );
	}
}
