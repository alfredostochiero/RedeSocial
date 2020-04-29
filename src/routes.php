<?php
use core\Router;

$router = new Router();

// parametro do get('url','controller@action')

$router->get('/', 'HomeController@index'); 
$router->get('/login','LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

$router->get('/cadastro','LoginController@signup');
$router->post('/cadastro','LoginController@signupAction');

$router->post('/post/new','PostController@new');

$router->get('/perfil/{id}','ProfileController@index'); // primeiro a especifica, depois a rota mais geral
$router->get('/perfil','ProfileController@index');

$router->get('/sair','LoginController@logout');


//$router->get('/pesquisar');

//$router->get('/sair');
//$router->get('/amigos');
//$router->get('/fotos');
//$router->get('/config');