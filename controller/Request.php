<?php

namespace Controller;

/**
 * Clase Request
 */
class Request
{
    // Uso de patrón de diseño Singleton
    use Singleton;

    /**
     * Constante string GET
     *
     * @var string
     */
    const GET  = "GET";
    /**
     * Constante string POST
     *
     * @var string
     */
    const POST  = "POST";

    /**
     * Dominio -> https://www.cnu-reports.com
     *
     * @var string
     */
    private string $domain;

    /**
     * URI Identificador Uniforme de Recurso.
     *
     * @var string
     */
    private string $path;

    /**
     * Métodos HTTP: GET, POST
     *
     * @var string
     */
    private string $method;

    /**
     * Arreglo de datos de los métodos HTTP: GET, POST
     *
     * @var array
     */
    private array $params;

    /**
     * Constructor de la clase Request
     *
     */
    public function __construct()
    {
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = array_merge($_POST, $_GET);
    }

    /**
     * Devuelve una URL (Localizador de Recursos Uniforme).
     *
     * @return string
     * 
     */
    public function getUrl(): string
    {
        return $this->domain . $this->path;
    }

    /**
     * Devuelve un Dominio https://www.cnu-reports.com
     *
     * @return string
     * 
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Devuelve una URI (Identificador Uniforme de Recurso).
     *
     * @return string
     * 
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Devuelve el tipo de método ejecutado en el servidor.
     *
     * @return string
     * 
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Verificación de tipo de método: POST
     *
     * @return bool
     * 
     */
    public function isPost(): bool
    {
        return $this->method === self::POST;
    }

    /**
     * Verificación de tipo de método: GET
     *
     * @return bool
     * 
     */
    public function isGet(): bool
    {
        return $this->method === self::GET;
    }

    /**
     * Datos recuperados a través de la petición GET o POST.
     *
     * @param string $name Nombre clave para recuperar dato del arreglo GET o POST.
     * 
     * @return mixed
     * 
     */
    public function get(string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * Arreglo de datos GET.
     *
     * @return array
     * 
     */
    public function paramsGET(): array
    {
        return $_GET;
    }

    /**
     * Validación de dato entero del arreglo GET o POST.
     *
     * @param string $name Nombre clave para recuperar dato del arreglo GET o POST.
     * 
     * @return int
     * 
     */
    public function getInt(string $name): int
    {
        return (int) $this->get($name);
    }

    /**
     * Validación de dato flotante del arreglo GET o POST.
     *
     * @param string $name Nombre clave para recuperar dato del arreglo GET o POST.
     * 
     * @return float
     * 
     */
    public function getNumber(string $name): float
    {
        return (float) $this->get($name);
    }

    /**
     * Validación de cadena de texto del arreglo GET o POST.
     *
     * @param string $name Nombre clave para recuperar dato del arreglo GET o POST.
     * @param bool $filter Por defecto es true, si es true se hace el 
     * filtro de carácteres especiales, sino se evita el filtro.
     * 
     * @return string
     * 
     */
    public function getString(string $name, $filter = true): string
    {
        $value = (string) $this->get($name);
        return $filter ?
            addslashes(
                htmlentities($value, ENT_QUOTES, 'UTF-8')
            ) : $value;
    }
}
