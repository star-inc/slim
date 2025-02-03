<?php

namespace Slim\Kernel;

use Slim\Controllers\ControllerInterface;
use Slim\Middlewares\MiddlewareInterface;
use Exception;
use TypeError;

class Router
{
    private array $routes = [];
    private array $middlewares_before = [];
    private array $middlewares_after = [];
    private ControllerInterface $controller;

    public function __construct(
        public string $class,
        public string $root_path = "/",
    )
    {
        self::initRouterBridge();
        $this->controller = new $class();
    }

    private static function initRouterBridge(): void
    {
        global $__JUSTIN_FRAMEWORK_ROUTER_BRIDGE;
        if (!isset($__JUSTIN_FRAMEWORK_ROUTER_BRIDGE)) {
            $__JUSTIN_FRAMEWORK_ROUTER_BRIDGE = [
                "version" => "1.0",
                "channels" => []
            ];
        }
    }

    private static function getRouterData(string $key): mixed
    {
        global $__JUSTIN_FRAMEWORK_ROUTER_BRIDGE;
        return $__JUSTIN_FRAMEWORK_ROUTER_BRIDGE[$key] ?? null;
    }

    private static function setRouterData(string $key, mixed $value): void
    {
        global $__JUSTIN_FRAMEWORK_ROUTER_BRIDGE;
        $__JUSTIN_FRAMEWORK_ROUTER_BRIDGE[$key] = $value;
    }

    public function addMiddleware(bool $type, string $controller): static
    {
        $instance = new $controller();
        if (!($instance instanceof MiddlewareInterface)) {
            throw new TypeError();
        }
        if ($type) {
            $this->middlewares_before[$controller] = $instance;
        } else {
            $this->middlewares_after[$controller] = $instance;
        }
        return $this;
    }

    public function deleteMiddleware(bool $type, string $controller): static
    {
        if ($type) {
            unset($this->middlewares_before[$controller]);
        } else {
            unset($this->middlewares_after[$controller]);
        }
        return $this;
    }

    private function executeMiddleware(bool $type, Context $context): void
    {
        $middlewares = $type ? $this->middlewares_before : $this->middlewares_after;
        foreach ($middlewares as $middleware) {
            if (is_null($middleware)) continue;
            assert($middleware instanceof MiddlewareInterface);
            $middleware::toUse($context);
        }
    }

    /**
     * @param string $http_method
     * @param string $path
     * @param string|callable $method
     * @return Router
     */
    public function register(string $http_method, string $path, string|callable $method): static
    {
        $path = !empty($path) && !str_starts_with($path, "/") ? "/$path" : $path;
        $path = $this->root_path === "/" ? $path : $this->root_path . $path;
        if (!array_key_exists($path, $this->routes)) {
            $this->routes[$path] = [];
        }
        $this->routes[$path][$http_method] = $method;
        return $this;
    }

    public function channel(): void
    {
        $clazz = get_class($this->controller);
        $channels = self::getRouterData("channels");
        if (array_key_exists($clazz, $channels)) return;
        $channels[$clazz] = $this;
        self::setRouterData("channels", $channels);
    }

    public static function run(): void
    {
        try {
            $context = new Context();
        } catch (Exception $e) {
            http_response_code(503);
            error_log($e->getMessage());
        }
        $channels = self::getRouterData("channels");
        $http_method = $context->getRequest()->getMethod();
        $http_path = parse_url($context->getRequest()->getRequestUri(), PHP_URL_PATH);
        $http_path = !str_starts_with($http_path, "/") ? "/$http_path" : $http_path;
        $found = false;
        foreach ($channels as $channel) {
            assert($channel instanceof static);
            if (!isset($channel->routes[$http_path][$http_method])) continue;
            $method_name = $channel->routes[$http_path][$http_method];
            // Do middlewares
            $channel->executeMiddleware(false, $context);
            // Main
            if (!($found = method_exists($channel->controller, $method_name))) continue;
            $channel->controller->$method_name($context);
            // Do middlewares
            $channel->executeMiddleware(true, $context);
        }
        if (!$found) {
            $context
                ->getResponse()
                ->setStatus(404)
                ->setBody([
                    "message" => "not found",
                    "description" => "no route register on $http_path"
                ])
                ->sendJSON();
        }
    }
}
