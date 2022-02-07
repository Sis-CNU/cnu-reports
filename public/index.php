<?php

use Controller\{
    Request,    
    Router
};

require_once('../vendor/autoload.php');

$router = Router::getInstance();
$request = Request::getInstance();
$response = $router->route($request);
echo $response;