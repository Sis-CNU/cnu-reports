<?php

declare(strict_types=1);

namespace App;

use App\HttpMethod;

/**
 * Clase Request
 */
class Request
{
    // Uso de patrón de diseño Singleton
    use Singleton;

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
     * Métodos HTTP: GET, POST, PUT y DELETE
     *
     * @var string
     */
    private string $method;

    /**
     * Arreglo de datos del método HTTP POST
     *
     * @var array
     */
    private array $params;

    /**
     * Constructor de la clase Request
     *
     */
    private function __construct()
    {
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->params = $_POST;
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
     * Verificación de tipo de método: GET
     *
     * @return bool
     *
     */
    public function isGet(): bool
    {        
        return $this->method === HttpMethod::GET->value;
    }

    /**
     * Verificación de tipo de método: POST
     *
     * @return bool
     *
     */
    public function isPost(): bool
    {        
        return $this->method === HttpMethod::POST->value;
    }

    /**
     * Verificación de tipo de método: PUT
     *
     * @return bool
     *
     */
    public function isPut(): bool
    {
        return $this->method === HttpMethod::PUT->value;
    }

    /**
     * Verificación de tipo de método: DELETE
     *
     * @return bool
     *
     */
    public function isDelete(): bool
    {
        return $this->method === HttpMethod::DELETE->value;
    }

    /**
     * Datos recuperados a través de la petición.
     *
     * @param string $name Nombre clave para recuperar dato del arreglo.
     *
     * @return mixed
     *
     */
    public function getData(string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * Validación de dato entero del arreglo GET o POST.
     *
     * @param string $name Nombre clave para recuperar dato del arreglo GET o POST.
     *
     * @return int
     *
     */
    public function getInteger(string $name): int
    {
        return (int) $this->getData($name);
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
        return (float) $this->getData($name);
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
        $value = (string) $this->getData($name);
        return $filter ?
            addslashes(
                htmlentities($value, ENT_QUOTES, 'UTF-8')
            ) : $value;
    }
}
