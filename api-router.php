<?php
require_once './libs/Router.php';
require_once './app/controllers/book-api.controller.php';

// crea el router
$router = new Router();


// defina la tabla de ruteo
$router->addRoute('books', 'GET', 'BookApiController', 'getBooks');
$router->addRoute('books/:ID', 'GET', 'BookApiController', 'getBook');
$router->addRoute('books/:ID', 'DELETE', 'BookApiController', 'deleteBook');
$router->addRoute('books', 'POST', 'BookApiController', 'insertBook'); 
$router->setDefaultRoute('BookApiController', 'pageNotFound');




// ejecuta la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);