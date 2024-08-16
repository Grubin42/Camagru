<?php

namespace Camagru\Core;

class Router {
    private array $routes = [];

    /**
     * Enregistre une route avec son callback.
     */
    public function addRoute(string $path, callable $callback): void {
        $this->routes[$path] = $callback;
    }

    /**
     * Exécute le callback associé à la route correspondante.
     */
    public function handleRequest(string $requestedPath): void {
        foreach ($this->routes as $path => $callback) {
            if ($path === $requestedPath) {
                $callback();
                return;
            }
        }
        // Si aucune route ne correspond, afficher une erreur 404
        http_response_code(404);
        echo "404 - Page not found";
    }
}