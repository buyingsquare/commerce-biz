<?php

class JsonadmTest extends \LocalWebTestCase
{
	public function testOptionsActionSite()
	{
		$response = $this->call( 'OPTIONS', '/invalid/jsonadm/product' );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertNotNull( $json );
	}


	public function testOptionsAction()
	{
		$response = $this->call( 'OPTIONS', '/unittest/jsonadm/product' );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'resources', $json['meta'] );
		$this->assertGreaterThan( 1, count( $json['meta']['resources'] ) );


		$response = $this->call( 'OPTIONS', '/unittest/jsonadm/' );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'resources', $json['meta'] );
		$this->assertGreaterThan( 1, count( $json['meta']['resources'] ) );
	}


	public function testActionsSingle()
	{
		$content = '{"data":{"type":"stock/type","attributes":{"stock.type.code":"slim","stock.type.label":"slim"}}}';
		$response = $this->call( 'POST', '/unittest/jsonadm/stock/type', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 201, $response->getStatusCode() );
		$this->assertArrayHasKey( 'stock.type.id', $json['data']['attributes'] );
		$this->assertEquals( 'slim', $json['data']['attributes']['stock.type.code'] );
		$this->assertEquals( 'slim', $json['data']['attributes']['stock.type.label'] );
		$this->assertEquals( 1, $json['meta']['total'] );

		$id = $json['data']['attributes']['stock.type.id'];


		$content = '{"data":{"type":"stock/type","attributes":{"stock.type.code":"slim2","stock.type.label":"slim2"}}}';
		$response = $this->call( 'PATCH', '/unittest/jsonadm/stock/type/' . $id, [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'stock.type.id', $json['data']['attributes'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['stock.type.code'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['stock.type.label'] );
		$this->assertEquals( $id, $json['data']['attributes']['stock.type.id'] );
		$this->assertEquals( 1, $json['meta']['total'] );


		$response = $this->call( 'GET', '/unittest/jsonadm/stock/type/' . $id );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'stock.type.id', $json['data']['attributes'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['stock.type.code'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['stock.type.label'] );
		$this->assertEquals( $id, $json['data']['attributes']['stock.type.id'] );
		$this->assertEquals( 1, $json['meta']['total'] );


		$response = $this->call( 'DELETE', '/unittest/jsonadm/stock/type/' . $id );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 1, $json['meta']['total'] );
	}


	public function testActionsBulk()
	{
		$content = '{"data":[
			{"type":"stock/type","attributes":{"stock.type.code":"slim","stock.type.label":"slim"}},
			{"type":"stock/type","attributes":{"stock.type.code":"slim2","stock.type.label":"slim"}}
		]}';
		$response = $this->call( 'POST', '/unittest/jsonadm/stock/type', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 201, $response->getStatusCode() );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertArrayHasKey( 'stock.type.id', $json['data'][0]['attributes'] );
		$this->assertArrayHasKey( 'stock.type.id', $json['data'][1]['attributes'] );
		$this->assertEquals( 'slim', $json['data'][0]['attributes']['stock.type.label'] );
		$this->assertEquals( 'slim', $json['data'][1]['attributes']['stock.type.label'] );
		$this->assertEquals( 2, $json['meta']['total'] );

		$ids = array( $json['data'][0]['attributes']['stock.type.id'], $json['data'][1]['attributes']['stock.type.id'] );


		$content = '{"data":[
			{"type":"stock/type","id":' . $ids[0] . ',"attributes":{"stock.type.label":"slim2"}},
			{"type":"stock/type","id":' . $ids[1] . ',"attributes":{"stock.type.label":"slim2"}}
		]}';
		$response = $this->call( 'PATCH', '/unittest/jsonadm/stock/type', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertArrayHasKey( 'stock.type.id', $json['data'][0]['attributes'] );
		$this->assertArrayHasKey( 'stock.type.id', $json['data'][1]['attributes'] );
		$this->assertEquals( 'slim2', $json['data'][0]['attributes']['stock.type.label'] );
		$this->assertEquals( 'slim2', $json['data'][1]['attributes']['stock.type.label'] );
		$this->assertTrue( in_array( $json['data'][0]['attributes']['stock.type.id'], $ids ) );
		$this->assertTrue( in_array( $json['data'][1]['attributes']['stock.type.id'], $ids ) );
		$this->assertEquals( 2, $json['meta']['total'] );


		$getParams = ['filter' => ['&&' => [
			['=~' => ['stock.type.code' => 'slim']],
			['==' => ['stock.type.label' => 'slim2']]
			]],
			'sort' => 'stock.type.code', 'page' => ['offset' => 0, 'limit' => 3]
		];
		$response = $this->call( 'GET', '/unittest/jsonadm/stock/type', $getParams );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertEquals( 'slim', $json['data'][0]['attributes']['stock.type.code'] );
		$this->assertEquals( 'slim2', $json['data'][1]['attributes']['stock.type.code'] );
		$this->assertEquals( 'slim2', $json['data'][0]['attributes']['stock.type.label'] );
		$this->assertEquals( 'slim2', $json['data'][1]['attributes']['stock.type.label'] );
		$this->assertTrue( in_array( $json['data'][0]['attributes']['stock.type.id'], $ids ) );
		$this->assertTrue( in_array( $json['data'][1]['attributes']['stock.type.id'], $ids ) );
		$this->assertEquals( 2, $json['meta']['total'] );


		$content = '{"data":[
			{"type":"stock/type","id":' . $ids[0] . '},
			{"type":"stock/type","id":' . $ids[1] . '}
		]}';
		$response = $this->call( 'DELETE', '/unittest/jsonadm/stock/type', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, $json['meta']['total'] );
	}


	public function testPutAction()
	{
		$content = '{"data":[
			{"type":"stock/type","attributes":{"stock.type.code":"slim","stock.type.label":"slim"}},
			{"type":"stock/type","attributes":{"stock.type.code":"slim2","stock.type.label":"slim"}}
		]}';
		$response = $this->call( 'PUT', '/unittest/jsonadm/stock/type', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertEquals( 501, $response->getStatusCode() );
		$this->assertNotNull( $json );
	}
}
