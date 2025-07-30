<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('gaji', ['filter' => 'auth', 'namespace' => 'App\Modules\Gaji\Controllers'], function($routes){
	$routes->add('/', 'Gaji::index', ['filter' => 'permission:gaji.view']);
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Gaji\Controllers\Api'], function($routes){
	$routes->get('gaji', 'Gaji::index', ['filter' => 'permission:gaji.view']);
	$routes->post('gaji/save', 'Gaji::create', ['filter' => 'permit:createGaji']);
	$routes->put('gaji/update/(:segment)', 'Gaji::update/$1', ['filter' => 'permit:updateGaji']);
	$routes->delete('gaji/delete/(:segment)', 'Gaji::delete/$1', ['filter' => 'permit:deleteGaji']);
});