<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('', ['namespace' => 'App\Modules\Home\Controllers'], function($routes){
	$routes->get('/', 'Home::index');
});

$routes->group('api', ['namespace' => 'App\Modules\Cuaca\Controllers\Api'], function($routes){
    $routes->get('display/cuaca', 'Cuaca::display');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Cuaca\Controllers\Api'], function($routes){
    $routes->get('cuaca', 'Cuaca::index');
});