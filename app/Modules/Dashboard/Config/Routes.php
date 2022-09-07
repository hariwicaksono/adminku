<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('dashboard', ['filter' => 'auth', 'namespace' => 'App\Modules\Dashboard\Controllers'], function($routes){
	$routes->add('/', 'Dashboard::index');
});

$routes->group('api', ['namespace' => 'App\Modules\News\Controllers\Api'], function($routes){
	$routes->add('news/news', 'News::news');
    $routes->add('news/info', 'News::info');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\News\Controllers\Api'], function($routes){
    $routes->add('news', 'News::index');
});