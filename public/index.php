<?php

use Controller\{
    Request,    
    Router
};

require_once('../vendor/autoload.php');

$router = new Router();
$response = $router->route(new Request());
echo $response;