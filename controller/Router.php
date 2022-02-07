<?php

namespace Controller;

/**
 * Clase Router
 */
class Router
{
    use Singleton;


    /**
     * Asignación de la variable rutas en otra variable.
     *
     * @var array
     */
    private array $routeMap;

    /**
     * 
     */
    private static $regexPatters = [
        'number' => '\d+',
        'string' => '\w+'
    ];

    /**
     * Constructor de la clase Router.
     */
    public function __construct()
    {
        // Incluyendo el archivo routes.php
        include_once('../routes/routes.php');

        // Impresión de la variable $this->routeMap        
        $this->routeMap = $routes;
    }

    public function route(Request $request)
    {
        /**
         * Obtenemos la ruta de la URL
         * https://dominio/this/is/my/path
         * siendo la ruta => /this/is/my/path
         */
        $path = $request->getPath();
        // echo "<pre>" . print_r($path, true) . "</pre>";

        echo "<pre>" . print_r($this->routeMap[$path], true) . "</pre>";



        // foreach ($this->routeMap as $route => $info) {

        //     $regexRoute = $this->getRegexRoute($route, $info);

        //     // echo "<pre>" . print_r($route, true) . "</pre>";
        //     // echo "<br>";

        //     // print_r($info);
        //     // echo "<br>";

        //     echo "<pre>" . print_r($regexRoute . "------------" . $path . "---------------" . preg_match("@^/$regexRoute$@", $path), true) . "</pre>";


        //     // echo "<br>";


        //     if (preg_match("@^/$regexRoute+$@", $path)) {
        //         return $this->executeController(
        //             $route,
        //             $path,
        //             $info
        //         );
        //     }
        // }
        return "Not found";
    }

    private function getRegexRoute(string $route, array $info): string
    {
        if (isset($info['params'])) {
            foreach ($info['params'] as $name => $type) {
                $route = str_replace(
                    ':' . $name,
                    self::$regexPatters[$type],
                    $route
                );
            }
        }
        return $route;
    }

    private function extractParams(string $route, string $path): array
    {
        $params = [];
        $pathParts = explode('/', $path);
        // print_r(var_dump($pathParts) . "<br>");

        $routeParts = explode('/', $route);
        // print_r(var_dump($routeParts) . "<br>");

        foreach ($routeParts as $key => $routePart) {

            // print_r($key . " => " . $routePart . "<br>");
            // print_r(strpos($routePart, ':') . "<br>");

            if (strpos($routePart, ':') === 0) {

                $name = substr($routePart, 1);
                // print_r($name);
                // print_r($pathParts[$key + 1] . "<br>");
                $params[$name] = $pathParts[$key + 1];
            }
        }
        return $params;
    }

    private function executeController(string $route, string $path, array $info)
    {
        // print_r($info['controller'] . " | " . $info['callback']  . " | " . $info['params']);
        $params = $this->extractParams($route, $path);
        // print_r(var_dump($params));
        // print_r($route . " | " . $path);

        return call_user_func_array(
            ["Controller\\{$info['controller']}", $info['callback']],
            $params
        );
        // echo "<br>";        
    }
}
