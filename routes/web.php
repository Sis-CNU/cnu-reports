<?php

declare(strict_types=1);

use App\Router;
use Controller\MainController;

Router::get('/', [MainController::class, 'index']);

// Router::post('/about', [MainController::class, 'about']);