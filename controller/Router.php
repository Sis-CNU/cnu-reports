<?php

namespace Controller;

use Controller\Response;
use Controller\Header;

/**
 * Clase Router
 */
class Router
{
    // Uso de patrón de diseño Singleton.
    use Singleton;

    /**
     * Asignación de la variable rutas en otra variable.
     *
     * @var array
     */
    private array $routeMap;

    /**
     * Patrones Regex para comparar las rutas establecidas
     * en el archivo routes.php y la URI ejecutada a través del navegador.
     * 'string' => '\w+' // [a-zA-Z0-9_]
     */
    private static array $regexPatterns = [
        /**
         * Este patrón solo acepta carácteres numéricos.
         */
        'number' => '\d+',
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
        // Incluyendo o carga del archivo routes.php
        include_once('../routes/routes.php');

        // Asignación de la variable $this->routeMap
        $this->routeMap = $routes;
    }


    /**
     * [Description for route]
     *
     * @param Request $request
     * 
     * @return [type]
     * 
     */
    public function route(Request $request)
    {
        /**
         * Obtenemos la ruta URI de la URL
         * https://domain/this/is/my/path
         * siendo la ruta => /this/is/my/path
         */
        $path = $request->getPath();

        // echo $path; // Haciendo pruebas desde aquí

        /**
         * Redireccionando a la URI permitida por el framework
         * si existe 
         */
        if (!empty($request->paramsGET())) {
            $response = Response::getInstance();
            return $response->redirect($path);
        }

        /**
         * Ejemplo : Entrada -> /this/is/my/path; Salida -> this/is/my/path
         * Remplazando el carácter '/' por cadena vacía al principio.
         */
        $path = ($path === '/') ? $path : substr_replace($path, "", 0, 1);

        /**
         * Revisando si la URI también contiene al final un carácter '/' 
         * y no es exactamente un '/' lo elimina.
         */
        $path = (substr($path, -1) === "/" && $path !== '/') ?
            substr_replace($path, "", strlen($path) - 1, 1) : $path;

        /**
         * Encontrando coincidencias entre las rutas y la URI.
         * haciendo una búsqueda por grupos y luego una búsqueda por cada
         * ítem de grupo.
         */
        $matchRoute = $this->match($path, $this->routeMap);

        if ($matchRoute) { // Si la ruta coincide con la URI
            // Ejecución del callback
            return $this->executeController($matchRoute["route"],  $path, $matchRoute["info"]);
        } else {
            // Se envía un mensaje de error 404 not found.
            Header::headerResponse(404);
        }
    }

    /**
     * Encontrando coincidencia con la URI y el arreglo de rutas establecidas.
     *
     * @param string $path Cadena de texto que representa la URI.
     * @param array $routes Arreglo de rutas o mapa de rutas.
     * 
     * @return bool|array
     * 
     */
    private function match(string $path, array $routes)
    {
        // Si la URI o el arreglo de rutas está vacio devolvera falso.
        if (empty($path) || empty($routes)) {
            return false;
        }

        /**
         * Dividiendo la URI en dos niveles separado por el carácter '/'
         * asignandolo en un array que contendrá 2 elementos.
         */
        $levels = ($path === '/') ? array('/') : explode('/', $path, 2);

        /**
         * Recuperación del primer nivel del arreglo de rutas
         */
        $info = $routes[$levels[0]];

        try {
            // Si dentro del primer nivel existe un controlador, una función o la ruta es exactamente un '/'
            if (
                isset($info['controller']) || array_key_exists('controller', $info) &&
                isset($info['callback']) || array_key_exists('callback', $info)
            ) {
                if ($levels[0] === $path) {
                    // Devuelve tanto la ruta, como el controlador y su función a ejecutarse.                
                    return ["route" => $path, "info" => $info];
                }
            }

            // Si existe un segundo nivel
            if (isset($levels[1]) || array_key_exists(1, $levels) && !empty($levels[1])) {
                // Recorrerá las subrutas que existen hasta encontrar una coincidencia 
                // a través del patrón regex regresando la ruta y su información 
                // (controlador y función)
                foreach ($info as $key => $value) {

                    // Estableciendo la ruta con su patrón regex
                    // Dirección -> http://www.dominio.com/research/5/all-events/category/kana_hanazawa
                    // Entrada -> 5/all-events/category/kana_hanazawa
                    // Salida -> \d+\/all-events\/category\/[^-_][A-Za-z0-9-_]+[^\W_]
                    $regexRoute = $this->getRegexRoute($key, $value);

                    if (preg_match("@^$regexRoute$@", $levels[1])) {
                        return ["route" => $levels[0] . "/" . $key, "info" => $info[$key]];
                    }
                }
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Conversión de ruta URI a URI con patrón regex.
     * Ruta        ->  :id/all-events/category/:name
     * Entrada URI ->  5/all-events/category/kana_hanazawa                
     * Salida      ->  \d+\/all-events\/category\/[^-_][A-Za-z0-9-_]+[^\W_]
     *
     * @param string $route Ruta URI a convertir
     * @param array $info Valor del arreglo de rutas, información (Controlador, Función y Parámetros)
     * 
     * @return string
     * 
     */
    private function getRegexRoute(string $route, array $info): string
    {
        if (isset($info['params'])) { // Si la ruta contiene parámetros
            foreach ($info['params'] as $name => $type) {
                /**
                 * Reemplazando URI por Cadena con patrón regex en URI.
                 * Ruta        ->  :id/all-events/category/:name
                 * Entrada URI ->  5/all-events/category/kana_hanazawa                
                 * Salida      ->  \d+\/all-events\/category\/[^-_][A-Za-z0-9-_]+[^\W_]
                 */
                $route = str_replace(
                    ':' . $name,
                    self::$regexPatterns[$type],
                    $route
                );
            }
        }
        return $route;
    }

    /**
     * Extracción de parámetros si la RUTA contiene parámetros
     * devolviendolo como un arreglo de los mismos.
     * Entrada     ->  :id/all-events/category/:name
     * Entrada     ->  5/all-events/category/kana_hanazawa                
     * Salida      ->  ['id' => 5, 'name' => kana_hanazawa]
     *
     * @param string $route Ruta con parametros
     * @param string $path URI
     * 
     * @return array
     * 
     */
    private function extractParams(string $route, string $path): array
    {
        $params = [];
        $pathParts = explode('/', $path);
        $routeParts = explode('/', $route);

        foreach ($routeParts as $key => $routePart) {
            if (strpos($routePart, ':') === 0) {
                $name = substr($routePart, 1);
                $params[$name] = $pathParts[$key];
            }
        }
        return $params;
    }

    /**
     * Ejecución de callback o función desde una clase controladora.
     *
     * @param string $route Ruta
     * @param string $path URI
     * @param array $info Controlador, Función y Parámetros
     * 
     * @return mixed La función se ejecuta, sino devolverá un false cuando suceda un error.
     * 
     */
    private function executeController(string $route, string $path, array $info)
    {
        // Extracción de parámetros.
        $params = $this->extractParams($route, $path);

        // Nombre de la clase a través de nombres de resolución.
        $class = __NAMESPACE__ . '\\' . $info['controller'];

        // Instanciación de la clase.
        $instance = $class::getInstance();

        // Llamado y ejecución de función.
        return call_user_func_array(
            [$instance, $info['callback']],
            $params
        );
    }

    public static function get(string $url, callable $callback)
    {
        echo $url . $callback();

    }

    public static function post(string $url, callable $callback)
    {
        echo $url . $callback();
    }

    public static function put(string $url, callable $callback)
    {
        echo $url . $callback();
    }

    public static function delete(string $url, callable $callback)
    {
        echo $url . $callback();
    }
}
