<?php

declare(strict_types=1);

namespace App;

use App\Router;

$app = function () {   
   return Router::getInstance()->route();
};
