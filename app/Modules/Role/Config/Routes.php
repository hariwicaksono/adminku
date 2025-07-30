<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('role', ['filter' => 'auth', 'namespace' => 'App\Modules\Role\Controllers'], function($routes){
	$routes->add('/', 'Role::index');
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Role\Controllers\Api'], function($routes){
	$routes->get('role', 'Role::index');
	$routes->post('role/save', 'Role::create');
	$routes->put('role/update/(:segment)', 'Role::update/$1');
	$routes->delete('role/delete/(:segment)', 'Role::delete/$1');
	$routes->get('role/(:num)/permissions', 'Role::getPermissions/$1');
	$routes->post('role/update-permissions/(:num)', 'Role::updatePermissions/$1');
});