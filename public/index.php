<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\BookController;
use App\Controllers\HomeController;

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/api/books', [BookController::class, 'index']);
$router->get('/api/books/{id}', [BookController::class, 'show']);
$router->post('/api/books', [BookController::class, 'store']);
$router->put('/api/books/{id}', [BookController::class, 'update']);
$router->patch('/api/books/{id}', [BookController::class, 'update']);
$router->delete('/api/books/{id}', [BookController::class, 'destroy']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
