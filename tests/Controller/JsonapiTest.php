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
		$getParams = ['filter' => ['f_search' => 'Cafe Noire Cap', 'f_listtype' => 'unittype19']];
		$response = $this->call( 'GET', '/unittest/jsonapi/product', $getParams );
		$json = json_decode( $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 1, $json['meta']['total'] );
		$this->assertEquals( 1, count( $json['data'] ) );
		$this->assertArrayHasKey( 'id', $json['data'][0] );
		$this->assertEquals( 'CNC', $json['data'][0]['attributes']['product.code'] );

		$id = $json['data'][0]['id'];


		$response = $this->call( 'GET', '/unittest/jsonapi/product/' . $id );
		$json = json_decode( $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 1, $json['meta']['total'] );
		$this->assertArrayHasKey( 'id', $json['data'] );
		$this->assertEquals( 'CNC', $json['data']['attributes']['product.code'] );
	}
}
