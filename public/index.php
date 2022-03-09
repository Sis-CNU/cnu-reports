<?php

require_once('../vendor/autoload.php');

use Controller\{
    Request,    
    Router
};

$router = Router::getInstance();
$request = Request::getInstance();
$response = $router->route($request);

echo $response;
