<?php

namespace Camagru\Core;

use Camagru\Presentation\Controllers\ErrorController;

class Router {
    private array $routes = [];
    private array $middleware = [];

    /**
     * Enregistre une route avec son callback.
     */
    public function addRoute(string $path, callable $callback): void {
        $this->routes[$path] = $callback;
    }

    /**
     * Ajoute un middleware à exécuter avant les routes.
     */
    public function addMiddleware(callable $middleware): void {
        $this->middleware[] = $middleware;
    }

    /**
     * Exécute le callback associé à la route correspondante.
     */
    public function handleRequest(string $requestedPath): void {
        // Exécute tous les middlewares
        foreach ($this->middleware as $middleware) {
            $middleware();
        }

        foreach ($this->routes as $path => $callback) {
            if ($path === $requestedPath) {
                $callback();
                return;
            }
        }
        // Si aucune route ne correspond, afficher une erreur 404
        $errorController = new ErrorController();
        $errorController->show404();
    }
}