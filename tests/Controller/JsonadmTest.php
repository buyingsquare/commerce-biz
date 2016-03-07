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


		$response = $this->call( 'OPTIONS', '/unittest/jsonadm' );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'resources', $json['meta'] );
		$this->assertGreaterThan( 1, count( $json['meta']['resources'] ) );
	}


	public function testActionsSingle()
	{
		$content = '{"data":{"type":"product/stock/warehouse","attributes":{"product.stock.warehouse.code":"slim","product.stock.warehouse.label":"slim"}}}';
		$response = $this->call( 'POST', '/unittest/jsonadm/product/stock/warehouse', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 201, $response->getStatusCode() );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data']['attributes'] );
		$this->assertEquals( 'slim', $json['data']['attributes']['product.stock.warehouse.code'] );
		$this->assertEquals( 'slim', $json['data']['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( 1, $json['meta']['total'] );

		$id = $json['data']['attributes']['product.stock.warehouse.id'];


		$content = '{"data":{"type":"product/stock/warehouse","attributes":{"product.stock.warehouse.code":"slim2","product.stock.warehouse.label":"slim2"}}}';
		$response = $this->call( 'PATCH', '/unittest/jsonadm/product/stock/warehouse/' . $id, [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data']['attributes'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['product.stock.warehouse.code'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( $id, $json['data']['attributes']['product.stock.warehouse.id'] );
		$this->assertEquals( 1, $json['meta']['total'] );


		$response = $this->call( 'GET', '/unittest/jsonadm/product/stock/warehouse/' . $id );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data']['attributes'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['product.stock.warehouse.code'] );
		$this->assertEquals( 'slim2', $json['data']['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( $id, $json['data']['attributes']['product.stock.warehouse.id'] );
		$this->assertEquals( 1, $json['meta']['total'] );


		$response = $this->call( 'DELETE', '/unittest/jsonadm/product/stock/warehouse/' . $id );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 1, $json['meta']['total'] );
	}


	public function testActionsBulk()
	{
		$content = '{"data":[
			{"type":"product/stock/warehouse","attributes":{"product.stock.warehouse.code":"slim","product.stock.warehouse.label":"slim"}},
			{"type":"product/stock/warehouse","attributes":{"product.stock.warehouse.code":"slim2","product.stock.warehouse.label":"slim"}}
		]}';
		$response = $this->call( 'POST', '/unittest/jsonadm/product/stock/warehouse', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 201, $response->getStatusCode() );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data'][0]['attributes'] );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data'][1]['attributes'] );
		$this->assertEquals( 'slim', $json['data'][0]['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( 'slim', $json['data'][1]['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( 2, $json['meta']['total'] );

		$ids = array( $json['data'][0]['attributes']['product.stock.warehouse.id'], $json['data'][1]['attributes']['product.stock.warehouse.id'] );


		$content = '{"data":[
			{"type":"product/stock/warehouse","id":' . $ids[0] . ',"attributes":{"product.stock.warehouse.label":"slim2"}},
			{"type":"product/stock/warehouse","id":' . $ids[1] . ',"attributes":{"product.stock.warehouse.label":"slim2"}}
		]}';
		$response = $this->call( 'PATCH', '/unittest/jsonadm/product/stock/warehouse', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data'][0]['attributes'] );
		$this->assertArrayHasKey( 'product.stock.warehouse.id', $json['data'][1]['attributes'] );
		$this->assertEquals( 'slim2', $json['data'][0]['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( 'slim2', $json['data'][1]['attributes']['product.stock.warehouse.label'] );
		$this->assertTrue( in_array( $json['data'][0]['attributes']['product.stock.warehouse.id'], $ids ) );
		$this->assertTrue( in_array( $json['data'][1]['attributes']['product.stock.warehouse.id'], $ids ) );
		$this->assertEquals( 2, $json['meta']['total'] );


		$getParams = ['filter' => ['&&' => [
			['=~' => ['product.stock.warehouse.code' => 'slim']],
			['==' => ['product.stock.warehouse.label' => 'slim2']]
			]],
			'sort' => 'product.stock.warehouse.code', 'page' => ['offset' => 0, 'limit' => 3]
		];
		$response = $this->call( 'GET', '/unittest/jsonadm/product/stock/warehouse', $getParams );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, count( $json['data'] ) );
		$this->assertEquals( 'slim', $json['data'][0]['attributes']['product.stock.warehouse.code'] );
		$this->assertEquals( 'slim2', $json['data'][1]['attributes']['product.stock.warehouse.code'] );
		$this->assertEquals( 'slim2', $json['data'][0]['attributes']['product.stock.warehouse.label'] );
		$this->assertEquals( 'slim2', $json['data'][1]['attributes']['product.stock.warehouse.label'] );
		$this->assertTrue( in_array( $json['data'][0]['attributes']['product.stock.warehouse.id'], $ids ) );
		$this->assertTrue( in_array( $json['data'][1]['attributes']['product.stock.warehouse.id'], $ids ) );
		$this->assertEquals( 2, $json['meta']['total'] );


		$content = '{"data":[
			{"type":"product/stock/warehouse","id":' . $ids[0] . '},
			{"type":"product/stock/warehouse","id":' . $ids[1] . '}
		]}';
		$response = $this->call( 'DELETE', '/unittest/jsonadm/product/stock/warehouse', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertNotNull( $json );
		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( 2, $json['meta']['total'] );
	}


	public function testPutAction()
	{
		$content = '{"data":[
			{"type":"product/stock/warehouse","attributes":{"product.stock.warehouse.code":"slim","product.stock.warehouse.label":"slim"}},
			{"type":"product/stock/warehouse","attributes":{"product.stock.warehouse.code":"slim2","product.stock.warehouse.label":"slim"}}
		]}';
		$response = $this->call( 'PUT', '/unittest/jsonadm/product/stock/warehouse', [], $content );

		$json = json_decode( (string) $response->getBody(), true );

		$this->assertEquals( 501, $response->getStatusCode() );
		$this->assertNotNull( $json );
	}
}
