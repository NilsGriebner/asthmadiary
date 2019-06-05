<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\AsthmaDiary\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
	'resources' => [
		'value' => ['url' => '/api/values'],
		'measurement' => ['url' => '/api/measurements'],
	],
	'routes' => [
		[
			'name' => 'page#index',
			'url' => '/',
			'verb' => 'GET',
		],
		[
			'name' => 'page#measurements',
			'url' => '/measurements',
			'verb' => 'GET',
		],
		[
			'name' => 'page#statistics',
			'url' => '/statistics',
			'verb' => 'GET',
		],
	]
];
