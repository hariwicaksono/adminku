<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('api', ['filter' => 'jwtauth', 'namespace' => 'App\Modules\Visitor\Controllers\Api'], function($routes){
	$routes->get('visitors', 'Visitor::index');
});