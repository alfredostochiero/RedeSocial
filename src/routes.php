<?php
use core\Router;

$router = new Router();

// parametro do get('url','controller@action')

$router->get('/', 'HomeController@index'); 
$router->get('/sobre/{nome}', 'HomeController@sobreP');
$router->get('/sobre', 'HomeController@sobre');