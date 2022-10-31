<?php

declare(strict_types=1);

use App\Router;
use Controller\MainController;

Router::get('/', [MainController::class, 'index']);

// Router::get('/resources/{id:integer}', [MainController::class, 'index']);

// Router::get('/resources/{resourceId:string}/posts/{postId:integer}', [MainController::class, 'index']);

// Router::get('/tasks', [MainController::class, 'index']);

// Router::post('/tasks', [MainController::class, 'create']);

// Router::put('/tasks/{id:integer}', [MainController::class, 'update']);

// Router::delete('/tasks/{id:integer}', [MainController::class, 'delete']);
