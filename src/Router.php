<?php

declare(strict_types=1);

namespace Allan\TaskManager;

use Allan\TaskManager\Controller\TaskController;
use Throwable;

class Router
{
    public function dispatch(string $path): void
    {
        header('Content-Type: application/json');

        $method = $_SERVER['REQUEST_METHOD'];
        $controller = new TaskController();

        try {
            match ("$method $path") {
                'GET /task/list'       => $controller->list(),
                'POST /task/create'    => $controller->create(),
                'PUT /task/done'       => $controller->toggleDone(),
                'DELETE /task/delete'  => $controller->delete(),
                'PUT /task/update'     => $controller->update(),
                default                => throw new \Exception('Invalid route or method', 404)
            };
        } catch (Throwable $e) {
            http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
