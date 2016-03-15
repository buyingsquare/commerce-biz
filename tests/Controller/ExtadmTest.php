<?php

class ExtadmTest extends \LocalWebTestCase
{
	public function testIndexAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/extadm' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertRegexp( '#<script type="text/javascript">.*window.MShop = {#smu', (string) $response->getBody() );
	}


	public function testDoAction()
	{
		$response = $this->call( 'POST', '/unittest/admin/extadm/do' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertRegexp( '#{.*}#smu', (string) $response->getBody() );
	}


	public function testFileAction()
	{
		$response = $this->call( 'GET', '/unittest/admin/extadm/file' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertContains( 'Ext.', (string) $response->getBody() );
	}
}