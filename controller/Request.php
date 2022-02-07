<?php

namespace Controller;

class Request
{
    use Singleton;
    
    const GET  = "GET";
    const  POST  = "POST";

    private $domain;
    private $path;
    private $method;

    private $params;

    public function __construct()
    {
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];        
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = array_merge($_POST, $_GET);
    }

    public function getUrl(): string
    {
        return $this->domain . $this->path;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function isPost(): bool
    {
        return $this->method === self::POST;
    }

    public function isGet(): bool
    {
        return $this->method === self::GET;
    }

    public function get(string $name)
    {        
        return $this->params[$name] ?? null;
    }

    public function getInt(string $name)
    {
        return (int) $this->get($name);
    }

    public function getNumber(string $name)
    {
        return (float) $this->get($name);
    }

    public function getString(string $name, $filter = true)
    {
        $value = (string) $this->get($name);
        return $filter ? addslashes(htmlentities($value)) : $value;
    }
}
