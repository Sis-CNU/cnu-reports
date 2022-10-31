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

    /**
     * Patrones Regex para comparar las rutas establecidas
     * en el archivo routes.php y la URI ejecutada a través del navegador.
     * 'string' => '\w+' // [a-zA-Z0-9_]
     */
    private static array $regexPatterns = [
        /**
         * Este patrón solo acepta carácteres numéricos.
         */
        'integer' => '\d+',
        /**
         *  Este patrón acepta carácteres alfanuméricos separados
         *  por guión y guión bajo.
         * 
         *  Prohibido el uso de guión y guión bajo al inicio y final.
         */
        'string' => '[^-_][A-Za-z0-9-_]+[^\W_]'
    ];

    /**
     * Constructor de la clase Router.
     */
    private function __construct()
    {
        self::$request = Request::getInstance();
        self::$response = Response::getInstance();
        include_once('../routes/routes.php');
    }

    public function route()
    {
        $path = $this->removeBackslashAtStart(self::$request->getPath());
        return $this->matchPathWithRoutes($path);
    }

    private function matchPathWithRoutes(string $path)
    {
        $routes = $this->matchHttpMethod(self::$request->getMethod());
        foreach ($routes as $route => $controller) {
            $routeWithRegularExpression = $this->replaceWithRegularExpression($route);
            if (preg_match("@^$routeWithRegularExpression$@", $path)) {
                return $this->executeController($path, $route, $controller);
            }else {
                return self::$response->response(404);
            }
        }
    }

    private function removeBackslashAtStart(string $str)
    {
        return (substr($str, -1) === "/" && $str !== '/') ?
            substr_replace($str, "", strlen($str) - 1, 1) : $str;
    }

    private function removeBackslashAtEnd(string $str)
    {
        return ($str === '/') ? $str : substr_replace($str, "", 0, 1);
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

    private function parseParameters(string $route, array $parameterSections)
    {
        foreach ($parameterSections as $param) {
            [$variable, $type] = $this->getParameterArray($param);

            $route = str_replace(
                "{" . $variable . ":" . $type . "}",
                self::$regexPatterns[$type],
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
        return explode('/', $this->removeBackslashAtEnd($url));
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
        if (self::$request->isGet())
            self::$httpGetRoutes[$route] = $controller;
    }

    public static function post(string $route, array $controller)
    {
        if (self::$request->isPost())
            self::$httpPostRoutes[$route] = $controller;
    }

    public static function put(string $route, array $controller)
    {
        if (self::$request->isPut())
            self::$httpPutRoutes[$route] = $controller;
    }

    public static function delete(string $route, array $controller)
    {
        if (self::$request->isDelete())
            self::$httpDeleteRoutes[$route] = $controller;
    }

    private function matchHttpMethod(string $httpMethod)
    {
        return match ($httpMethod) {
            'get' => self::$httpGetRoutes,
            'post' => self::$httpPostRoutes,
            'put' => self::$httpPutRoutes,
            'delete' => self::$httpDeleteRoutes,
        };
    }
}
