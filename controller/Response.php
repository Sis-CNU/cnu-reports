<?php

namespace Controller;

/**
 * Clase Response
 */
class Response
{
    // Uso de patrón de diseño Singleton
    use Singleton;

    /**
     * Código de Status HTTP
     * 
     * @var array $status
     */
    private array $status = [
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    ];

    /**
     * Renderiza la vista html establecida por una ruta.
     *
     * @param string $view
     * @param array $data
     * 
     * @return string|false Vista.
     * 
     */
    public static function view(string $view, array $data = [])
    {
        if (!empty($data)) extract($data);
        ob_start();
        require_once($view);
        flush();
        echo ob_get_clean();
    }

    /**
     * Renderiza y ejecuta código php en cualquier tipo y extensión de archivo.
     *
     * @param string $view
     * @param array $data
     * 
     * @return string|false Contenido renderizado
     * 
     */
    public function render(string $view, array $data = [])
    {
        if (!empty($data)) extract($data);
        ob_start();
        require_once($view);
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    /**
     * Redireccionamiento de páginas.
     *
     * @param string $url
     * @param array $data
     * 
     * @return never
     * 
     */
    public function redirect(string $url, array $data = []): never
    {
        session_start();
        $_SESSION['data'] = $data;
        header("Location: $url");
        exit();
    }

    /**
     * Devuelve conjunto de datos json como API.
     *
     * @param array $data
     * @param int $code
     * 
     * @return 
     * 
     */
    public function json(array $data, int $code): never
    {
        ob_start();
        header_remove();
        header("Cache-Control: private, max-age=300, s-maxage=900");
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("Content-Security-Policy: script-src 'self'");
        header("Content-type: application/json; charset=utf-8");
        header("Status: " . $this->status[$code]);
        http_response_code($code);
        echo json_encode(['data' => $data], JSON_PRETTY_PRINT);
        exit();
    }
}
