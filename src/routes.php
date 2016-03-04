<?php


$app->group( '/', function() {

	$this->map(['GET'], 'extadm', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Extadm::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_extadm' );

	$this->map(['GET'], 'extadm/file', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Extadm::fileAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_extadm_file' );

	$this->map(['POST'], 'extadm/do', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Extadm::doAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_extadm_json' );


	$this->map(['GET', 'POST'], 'jqadm/copy/{resource:[^0-9]+}/{id:[0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::copyAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_copy' );

	$this->map(['GET', 'POST'], 'jqadm/create/{resource:[^0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::createAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_create' );

	$this->map(['GET', 'POST'], 'jqadm/delete/{resource:[^0-9]+}/{id:[0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::deleteAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_delete' );

	$this->map(['GET'], 'jqadm/get/{resource:[^0-9]+}/{id:[0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::getAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_get' );

	$this->map(['POST'], 'jqadm/save/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::saveAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_save' );

	$this->map(['GET', 'POST'], 'jqadm/search/{resource:[^0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::createAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_search' );


	$this->map(['DELETE'], 'jsonadm/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::deleteAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_delete' );

	$this->map(['GET'], 'jsonadm/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::getAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_get' );

	$this->map(['PATCH'], 'jsonadm/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::patchAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_patch' );

	$this->map(['POST'], 'jsonadm/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::postAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_post' );

	$this->map(['PUT'], 'jsonadm/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::putAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_put' );

	$this->map(['OPTIONS'], 'jsonadm[/{resource}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::optionsAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_options' );

});


$app->group( $config( 'routes/account', '/' ), function() {

	$this->map(['GET', 'POST'], 'myaccount', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account' );

	$this->map(['GET', 'POST'], 'myaccount/favorite', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account_favorite' );

	$this->map(['GET', 'POST'], 'myaccount/watch', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account_watch' );

	$this->map(['GET', 'POST'], 'myaccount/download/{dl_id}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::downloadAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account_download' );

});


$app->group( $config( 'routes/default', '/' ), function() {

	$this->map(['GET', 'POST'], 'count', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::countAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_count' );

	$this->map(['GET', 'POST'], 'detail/{d_prodid}[/{d_name}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::detailAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_detail' );

	$this->map(['GET', 'POST'], 'detail/pin', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::detailAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_session_pinned' );

	$this->map(['GET', 'POST'], 'list', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::listAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_list' );

	$this->map(['GET', 'POST'], 'suggest', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::suggestAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_suggest' );

	$this->map(['GET', 'POST'], 'stock', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::stockAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_stock' );

	$this->map(['GET', 'POST'], 'basket', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Basket::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_basket' );

	$this->map(['GET', 'POST'], 'checkout[/{c_step}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Checkout::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_checkout' );

});


$app->group( $config( 'routes/confirm', '/' ), function() {

	$this->map(['GET', 'POST'], 'confirm', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Checkout::confirmAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_confirm' );

});


$app->group( $config( 'routes/update', '/' ), function() {

	$this->map(['GET', 'POST'], 'update', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Checkout::updateAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_update' );

});
