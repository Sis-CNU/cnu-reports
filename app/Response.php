<?php

declare(strict_types=1);

namespace App;

use App\Header;

/**
 * Clase Response
 */
class Response
{
    // Uso de patrón de diseño Singleton
    use Singleton;

    private static Request $request;

    private function __construct()
    {
        self::$request = Request::getInstance();
    }

    /**
     * Renderiza la vista html establecida por una ruta.
     *
     * @param string $view Directorio del archivo vista.
     * @param array $data Arreglo de datos que se mandan a la vista.     
     */
    public function view(string $view, array $data = [])
    {
        try {
            if (file_exists($view) && !empty($view) && $view != null) {
                if (!empty($data)) extract($data);

                ob_start();
                require_once($view);
                Header::headerResponse(200);
                flush();
                echo ob_get_clean();
            } else {
                Header::headerResponse(404);
            }
        } catch (\Throwable) {
            Header::headerResponse(500);
        }
    }

    /**
     * Renderiza y ejecuta código php en cualquier tipo y extensión de archivo.
     *
     * @param string $view Directorio del archivo vista a renderizar.
     * @param array $data Arreglo de datos que se mandan a la vista.
     *
     * @return string|false Contenido renderizado
     *
     */
    public function render(string $view, array $data = []): string|false
    {
        try {
            if (file_exists($view) && !empty($view) && $view != null) {
                if (!empty($data)) extract($data);

                ob_start();
                require_once($view);
                Header::headerResponse(200);
                $content = ob_get_contents();
                ob_get_clean();
                return $content;
            } else {
                Header::headerResponse(404);
            }
        } catch (\Throwable) {
            Header::headerResponse(500);
        }
    }

    /**
     * Redireccionamiento de páginas.
     *
     * @param string $url URL a redireccionar.
     * @param array $data Arreglo de datos que se mandan con la redirección.
     *
     * @return never
     *
     */
    public function redirect(string $url, array $data = []): never
    {
        try {
            if (!empty($data)) {
                session_start();
                $_SESSION['data'] = $data;
            }

            Header::redirectHeaders($url);
        } catch (\Throwable) {
            Header::headerResponse(500);
        } finally {
            exit();
        }
    }

    /**
     * Devuelve conjunto de datos json como API.
     *
     * @param array $data Arreglo de datos.
     * @param int $code Código de status http.
     *
     * @return never
     *
     */
    public function json(array $data, int $code = 200): never
    {
        try {
            ob_start();
            Header::apiHeaderResponse($code);
            echo json_encode(['data' => $data]);
        } catch (\Throwable) {
            Header::headerResponse(500);
        } finally {
            exit();
        }
    }

    public function response(int $code)
    {
        try {
            $headerFilter = self::$request->getHeader('Accept');        

            if ($headerFilter != "application/json") {
                Header::headerResponse($code);
            } else {
                Header::apiHeaderResponse($code);
            }
        } catch (\Throwable) {
            Header::headerResponse(500);
        }
    }
}
