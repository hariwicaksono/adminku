<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('user', ['filter' => 'auth', 'namespace' => 'App\Modules\User\Controllers'], function($routes){
	$routes->add('/', 'User::index', ['filter' => 'permit:viewUser']);
});

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\User\Controllers\Api'], function($routes){
	$routes->get('user', 'User::index', ['filter' => 'permit:viewUser']);
	$routes->post('user/save', 'User::create', ['filter' => 'permit:createUser']);
	$routes->put('user/update/(:segment)', 'User::update/$1', ['filter' => 'permit:updateUser']);
	$routes->delete('user/delete/(:segment)', 'User::delete/$1', ['filter' => 'permit:deleteUser']);
	$routes->put('user/setActive/(:segment)', 'User::setActive/$1', ['filter' => 'permit:updateUser']);
	$routes->put('user/setRole/(:segment)', 'User::setRole/$1', ['filter' => 'permit:updateUser']);
	$routes->post('user/changePassword', 'User::changePassword', ['filter' => 'permit:updateUser']);
	$routes->put('user/setgroup/(:segment)', 'User::setGroup/$1', ['filter' => 'permit:updateUser']);
});