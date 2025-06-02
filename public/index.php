<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Allan\TaskManager\Router;

$router = new Router();

// Get clean URL path (remove query string, trailing slash)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');

$router->dispatch($path);
