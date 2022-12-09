<?php

declare(strict_types=1);

namespace App;

use App\{Request, Response};

/**
 * Clase Router
 */
class Router
{
    // Uso de patrón de diseño Singleton.
    use Singleton;

    private static Response $response;

    private static Request $request;

    private static array $httpGetRoutes = [];

    private static array $httpPostRoutes = [];

    private static array $httpPutRoutes = [];

    private static array $httpDeleteRoutes = [];

    private static string $prefix = '';

    private static string $typeOfService = '';

    /**
     * Constructor de la clase Router.
     */
    private function __construct()
    {
        self::$request = Request::getInstance();
        self::$response = Response::getInstance();

        $this->includeRoutes("api");
        $this->includeRoutes("web");
    }

    private function includeRoutes(string $typeOfService)
    {
        self::$typeOfService = $typeOfService;
        if (self::$typeOfService === "api")
            include_once('../routes/api.php');

        if (self::$typeOfService === "web")
            include_once('../routes/web.php');
    }

    public function route()
    {
        $path = self::removeBackslashAtEnd(self::$request->getPath());
        return $this->matchPathWithRoutes($path);
    }

    private function matchPathWithRoutes(string $path)
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route => $controller) {
            $routeWithRegularExpression = $this->replaceWithRegularExpression($route);
            if (preg_match("@^$routeWithRegularExpression$@", $path)) {
                return $this->executeController($path, $route, $controller);
            }
        }
        
        return self::$response->response(404);
    }

    private static function removeBackslashAtStart(string $str)
    {
        return ($str === '/') ? $str : substr_replace($str, "", 0, 1);
    }

    private static function removeBackslashAtEnd(string $str)
    {
        return (substr($str, -1) === "/" && $str !== '/') ?
            substr_replace($str, "", strlen($str) - 1, 1) : $str;
    }

    private function replaceWithRegularExpression(string $route): string
    {
        $parameterSections = $this->getParameterSections($route);
        $route = $this->parseParameters($route, $parameterSections);
        return $route;
    }

    private function executeController(string $path, string $route, array $controller)
    {
        $params = $this->getParameters($path, $route);
        [$class, $callback] = $controller;
        return call_user_func_array([$class::getInstance(), $callback], $params);
    }

    private function getParameterSections(string $route): array
    {
        $parameterSections = [];
        preg_match_all("/{[^\W_]+:[^\W_]+}/", $route, $parameterSections);
        return $parameterSections[0];
    }

    private static function getRegexOfType(string $type): string
    {
        return match ($type) {
            'integer' => '\d+',
            'string' => '[^-_][A-Za-z0-9-_]+[^\W_]'
        };
    }

    private function parseParameters(string $route, array $parameterSections)
    {
        foreach ($parameterSections as $param) {
            [$variable, $type] = $this->getParameterArray($param);

            $route = str_replace(
                "{" . $variable . ":" . $type . "}",
                self::getRegexOfType($type),
                $route
            );
        }
        return $route;
    }

    private function getParameters(string $path, string $route)
    {
        $chunksOfPath = $this->extractChunksFromUrl($path);
        $chunksOfRoute = $this->extractChunksFromUrl($route);
        return $this->extractParameters($chunksOfPath, $chunksOfRoute);
    }

    private function getParameterArray(string $param): array
    {
        $param = explode(":", $this->removeBracketsFromParameters($param));
        return $param;
    }

    private function extractChunksFromUrl($url)
    {
        return explode('/', self::removeBackslashAtStart($url));
    }

    private function extractParameters(array $chunksOfPath, array $chunksOfRoute)
    {
        $params = [];
        foreach ($chunksOfRoute as $key => $chunk) {
            if (strpos($chunk, '{') === 0) {
                preg_match("/{(.+?)\:/", $chunk, $matchedChunks);
                $name = $matchedChunks[1];
                $params[$name] = $chunksOfPath[$key];
            }
        }
        return $params;
    }

    private function removeBracketsFromParameters(string $param)
    {
        $param = substr_replace($param, "", 0, 1);
        $param = substr_replace($param, "", strlen($param) - 1, 1);
        return $param;
    }

    public static function get(string $route, array $controller)
    {
        if (self::$request->isGet()) {
            array_push($controller, HttpMethod::GET->value, self::$typeOfService);
            self::$httpGetRoutes[self::$prefix . $route] = $controller;
        }
    }

    public static function post(string $route, array $controller)
    {
        if (self::$request->isPost()) {
            array_push($controller, HttpMethod::POST->value, self::$typeOfService);
            self::$httpPostRoutes[self::$prefix . $route] = $controller;
        }
    }

    public static function put(string $route, array $controller)
    {
        if (self::$request->isPut()) {
            array_push($controller, HttpMethod::PUT->value, self::$typeOfService);
            self::$httpPutRoutes[self::$prefix . $route] = $controller;
        }
    }

    public static function delete(string $route, array $controller)
    {
        if (self::$request->isDelete()) {
            array_push($controller, HttpMethod::DELETE->value, self::$typeOfService);
            self::$httpDeleteRoutes[self::$prefix . $route] = $controller;
        }
    }

    private function getRoutes()
    {
        $routes = $this->filterRoutesByHttpMethod(self::$request->getMethod());
        return $this->filterRoutesByPrefix($routes);
    }

    private function filterRoutesByHttpMethod(string $method)
    {
        return match ($method) {
            "get" => self::$httpGetRoutes,
            "post" => self::$httpPostRoutes,
            "put" => self::$httpPutRoutes,
            "delete" => self::$httpDeleteRoutes,
        };
    }

    private function filterRoutesByPrefix(array $routes)
    {
        return array_filter($routes, function ($route) {
            return str_starts_with($route, self::$prefix);
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function prefix(string $prefix)
    {
        self::$prefix = "/$prefix";
        return new self;
    }

    public static function group(callable $callback)
    {
        $callback();
        if (!self::hasPathPrefix(self::$prefix))
            self::$prefix = '';
    }

    private static function extractFirstDirectoryFromPath(string $path)
    {
        $path = self::removeBackslashAtStart(self::$request->getPath());
        [$firstDirectory] = explode('/', $path);
        return $firstDirectory;
    }

    private static function hasPathPrefix(string $prefix)
    {
        return self::removeBackslashAtStart($prefix) ===
            self::extractFirstDirectoryFromPath(self::$request->getPath());
    }
}
