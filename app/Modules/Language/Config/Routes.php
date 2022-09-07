<?php

if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('', ['namespace' => 'App\Modules\Language\Controllers'], function($routes){
	$routes->get('/lang/{locale}', 'Lang::setLanguage');
});