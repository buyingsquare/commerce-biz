<?php


class JsonapiTest extends \LocalWebTestCase
{
	public function testOptionsAction()
	{
		$response = $this->call( 'OPTIONS', '/unittest/jsonapi/' );
		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'resources', $json['meta'] );
		$this->assertGreaterThan( 1, count( $json['meta']['resources'] ) );
	}


	public function testGetAction()
	{
		$getParams = ['filter' => ['f_search' => 'Cafe Noire Cap']];
		$response = $this->call( 'GET', '/unittest/jsonapi/product', $getParams );
		$json = json_decode( $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, $json['meta']['total'] );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertArrayHasKey( 'id', $json['data'][0] );
		$this->assertEquals( 'CNC', $json['data'][0]['attributes']['product.code'] );


		$response = $this->call( 'GET', '/unittest/jsonapi/product', ['id' => $json['data'][0]['id']] );
		$json = json_decode( $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 1, $json['meta']['total'] );
		$this->assertArrayHasKey( 'id', $json['data'] );
		$this->assertEquals( 'CNC', $json['data']['attributes']['product.code'] );
	}


	public function testPostPatchDeleteAction()
	{
		// get CNC product
		$params = ['filter' => ['f_search' => 'Cafe Noire Cap', 'f_listtype' => 'unittype19']];
		$response = $this->call( 'GET', '/unittest/jsonapi/product', $params );
		$json = json_decode( $response->getBody(), true );
		$this->assertEquals( 'CNC', $json['data'][0]['attributes']['product.code'] );

		// add CNC product to basket
		$params = ['id' => 'default', 'related' => 'product'];
		$content = json_encode( ['data' => ['attributes' => ['product.id' => $json['data'][0]['id']]]] );
		$response = $this->call( 'POST', '/unittest/jsonapi/basket', $params, $content );
		$json = json_decode( $response->getBody(), true );
		$this->assertEquals( 'CNC', $json['included'][0]['attributes']['order.base.product.prodcode'] );

		// change product quantity in basket
		$content = json_encode( ['data' => ['attributes' => ['quantity' => 2]]] );
		$response = $this->call( 'PATCH', '/unittest/jsonapi/basket', $params + ['relatedid' => 0], $content );
		$json = json_decode( $response->getBody(), true );
		$this->assertEquals( 2, $json['included'][0]['attributes']['order.base.product.quantity'] );

		// delete product from basket
		$response = $this->call( 'DELETE', '/unittest/jsonapi/basket', $params + ['relatedid' => 0] );
		$json = json_decode( $response->getBody(), true );
		$this->assertEquals( 0, count( $json['included'] ) );
	}


	public function testPutAction()
	{
		$response = $this->call( 'PUT', '/unittest/jsonapi/basket' );
		$json = json_decode( $response->getBody(), true );
		$this->assertArrayHasKey( 'errors', $json );
	}
}
