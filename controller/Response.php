<?php

namespace Controller;

use Controller\Header;

/**
 * Clase Response
 */
class Response
{
    // Uso de patrón de diseño Singleton
    use Singleton;

    /**
     * Renderiza la vista html establecida por una ruta.
     *
     * @param string $view Directorio del archivo vista.
     * @param array $data Arreglo de datos que se mandan a la vista.
     * 
     * @return string|false Vista.
     * 
     */
    public function view(string $view, array $data = [])
    {
        if (file_exists($view) && !empty($view) && $view != null) {
            if (!empty($data)) extract($data);
            Header::headerResponse(200);

            ob_start();
            require_once($view);
            flush();
            echo ob_get_clean();
        } else {
            Header::headerResponse(404);
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
    public function render(string $view, array $data = [])
    {
        if (file_exists($view) && !empty($view) && $view != null) {
            if (!empty($data)) extract($data);
            Header::headerResponse(200);

            ob_start();
            require_once($view);
            $content = ob_get_contents();
            ob_get_clean();
            return $content;
        } else {
            Header::headerResponse(404);
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
        if (!empty($data)) {
            session_start();
            $_SESSION['data'] = $data;
        }
        Header::redirectHeaders($url);
        exit();
    }

    /**
     * Devuelve conjunto de datos json como API.
     *
     * @param array $data Arreglo de datos.
     * @param int $code Código de status http.
     * 
     * @return string|false
     * 
     */
    public function json(array $data, int $code): never
    {
        ob_start();
        Header::apiHeaderResponse($code);
        echo json_encode(['data' => $data]); // JSON_PRETTY_PRINT
        exit();
    }
}
