<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('permission', ['filter' => 'auth', 'namespace' => 'App\Modules\Permission\Controllers'], function($routes){
	$routes->add('/', 'Permission::index');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Permission\Controllers\Api'], function($routes){
	$routes->get('permission', 'Permission::index');
	$routes->post('permission/save', 'Permission::create');
	$routes->put('permission/update/(:segment)', 'Permission::update/$1');
	$routes->delete('permission/delete/(:segment)', 'Permission::delete/$1');
});