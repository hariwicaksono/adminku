<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('golongan', ['filter' => 'auth', 'namespace' => 'App\Modules\Golongan\Controllers'], function($routes){
	$routes->add('/', 'Golongan::index', ['filter' => 'permit:viewGolongan']);
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Golongan\Controllers\Api'], function($routes){
	$routes->get('golongan', 'Golongan::index', ['filter' => 'permit:viewGolongan']);
	$routes->get('golongan/(:segment)', 'Golongan::show/$1', ['filter' => 'permit:viewGolongan']);
	$routes->post('golongan/save', 'Golongan::create', ['filter' => 'permit:createGolongan']);
	$routes->put('golongan/update/(:segment)', 'Golongan::update/$1', ['filter' => 'permit:updateGolongan']);
	$routes->delete('golongan/delete/(:segment)', 'Golongan::delete/$1', ['filter' => 'permit:deleteGolongan']);
});