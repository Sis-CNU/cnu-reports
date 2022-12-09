<?php

declare(strict_types=1);

use App\Router;
use Controller\MainController;

// Router::prefix('tasks')->group(function () {
//    Router::get('/{taskId:integer}/descriptions/{descId:string}', [MainController::class, 'getDescription']);
// });

// Router::get('/resources/{id:integer}', [MainController::class, 'index']);

// Router::get('/resources/{resourceId:string}/posts/{postId:integer}', [MainController::class, 'getPost']);

Router::get('/tasks', [MainController::class, 'getTasks']); // Aprovado

Router::post('/tasks', [MainController::class, 'create']); //

Router::put('/tasks/{id:integer}', [MainController::class, 'update']);

Router::delete('/tasks/{id:integer}', [MainController::class, 'delete']);
