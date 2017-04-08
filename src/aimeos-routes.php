<?php


$app->group( $config( 'routes/admin', '/admin' ), function() use ( $config ) {

	$this->map(['GET', 'POST'], '', function( $request, $response, $args ) use ( $config ) {
		return $response->withHeader( 'Location', $config( 'routes/admin', '/admin' ) . '/jqadm/search/dashboard' );
	})->setName( 'aimeos_shop_admin' );


	$this->map(['GET'], '/extadm/file', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Extadm::fileAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_extadm_file' );

	$this->map(['POST'], '/extadm/do', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Extadm::doAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_extadm_json' );

	$this->map(['GET'], '/extadm[/{tab:[0-9]+}[/{lang}]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Extadm::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_extadm' );


	$this->map(['GET'], '/jqadm/file/{type}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::fileAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_file' );

	$this->map(['GET', 'POST'], '/jqadm/copy/{resource:[^0-9]+}/{id:[0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::copyAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_copy' );

	$this->map(['GET', 'POST'], '/jqadm/create/{resource:[^0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::createAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_create' );

	$this->map(['GET', 'POST'], '/jqadm/delete/{resource:[^0-9]+}/{id:[0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::deleteAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_delete' );

	$this->map(['GET'], '/jqadm/get/{resource:[^0-9]+}/{id:[0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::getAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_get' );

	$this->map(['POST'], '/jqadm/save/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::saveAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_save' );

	$this->map(['GET', 'POST'], '/jqadm/search/{resource:[^0-9]+}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jqadm::searchAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jqadm_search' );

});


$app->group( $config( 'routes/jsonadm', '/jsonadm' ), function() use ( $config ) {

	$this->map(['DELETE'], '/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::deleteAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_delete' );

	$this->map(['GET'], '/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::getAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_get' );

	$this->map(['PATCH'], '/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::patchAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_patch' );

	$this->map(['POST'], '/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::postAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_post' );

	$this->map(['PUT'], '/{resource:[^0-9]+}[/{id:[0-9]+}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::putAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_put' );

	$this->map(['OPTIONS'], '/[{resource}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonadm::optionsAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonadm_options' );

});


$app->group( $config( 'routes/jsonapi', '/jsonapi' ), function() use ( $config ) {

	$this->map(['DELETE'], '/{resource}[/{id}[/{related}[/{relatedid}]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonapi::deleteAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonapi_delete' );

	$this->map(['GET'], '/{resource}[/{id}[/{related}[/{relatedid}]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonapi::getAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonapi_get' );

	$this->map(['PATCH'], '/{resource}[/{id}[/{related}[/{relatedid}]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonapi::patchAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonapi_patch' );

	$this->map(['POST'], '/{resource}[/{id}[/{related}[/{relatedid}]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonapi::postAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonapi_post' );

	$this->map(['PUT'], '/{resource}[/{id}[/{related}[/{relatedid}]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonapi::putAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonapi_put' );

	$this->map(['OPTIONS'], '/[{resource}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Jsonapi::optionsAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_jsonapi_options' );

});


$app->group( $config( 'routes/account', '' ), function() {

	$this->map(['GET', 'POST'], '/myaccount', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account' );

	$this->map(['GET', 'POST'], '/myaccount/favorite[/{fav_action}/{fav_id:[0-9]+}[/{d_prodid:[0-9]+}[/{d_name}[/{d_pos:[0-9]+}]]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account_favorite' );

	$this->map(['GET', 'POST'], '/myaccount/watch[/{wat_action}/{wat_id:[0-9]+}[/{d_prodid:[0-9]+}[/{d_name}[/{d_pos:[0-9]+}]]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account_watch' );

	$this->map(['GET', 'POST'], '/myaccount/download/{dl_id}', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Account::downloadAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_account_download' );

});


$app->group( $config( 'routes/default', '' ), function() {

	$this->map(['GET', 'POST'], '/count', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::countAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_count' );

	$this->map(['GET', 'POST'], '/detail/{d_prodid:[0-9]+}[/{d_name}[/{d_pos:[0-9]+}]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::detailAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_detail' );

	$this->map(['GET', 'POST'], '/detail/pin[/{pin_action}/{pin_id:[0-9]+}[/{d_prodid:[0-9]+}[/{d_name}[/{d_pos:[0-9]+}]]]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::detailAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_session_pinned' );

	$this->map(['GET', 'POST'], '/list[/{f_catid:[0-9]+}[/{f_name}]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::listAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_list' );

	$this->map(['GET', 'POST'], '/suggest', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::suggestAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_suggest' );

	$this->map(['GET', 'POST'], '/stock', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Catalog::stockAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_stock' );

	$this->map(['GET', 'POST'], '/basket', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Basket::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_basket' );

	$this->map(['GET', 'POST'], '/checkout[/{c_step}]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Checkout::indexAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_checkout' );

});


$app->group( $config( 'routes/confirm', '' ), function() {

	$this->map(['GET', 'POST'], '/confirm[/{code}[/{orderid:[0-9]+}]]', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Checkout::confirmAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_confirm' );

});


$app->group( $config( 'routes/update', '' ), function() {

	$this->map(['GET', 'POST'], '/update', function( $request, $response, $args ) {
		return \Aimeos\Slim\Controller\Checkout::updateAction( $this, $request, $response, $args );
	})->setName( 'aimeos_shop_update' );

});


$app->map(['GET', 'POST'], '/terms', function( $request, $response, $args ) {
	return 'terms';
})->setName( 'aimeos_shop_terms' );

$app->map(['GET', 'POST'], '/privacy', function( $request, $response, $args ) {
	return 'privacy';
})->setName( 'aimeos_shop_privacy' );
