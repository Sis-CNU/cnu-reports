<?php

declare(strict_types=1);

namespace App;

use Controller\{
   Request,
   Router,
};

$app = function () {
   $router = Router::getInstance();
   $request = Request::getInstance();
   return $router->route($request);
};
