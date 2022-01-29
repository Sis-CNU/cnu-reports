<?php

namespace Controller;

class Response
{
    public function view($view, array $data = [])
    {
        if (!empty($data)) extract($data);
        ob_start();
        require_once($view);
        flush();
        echo ob_get_clean();
    }

    public function render($view, array $data = [])
    {
        if (!empty($data)) extract($data);
        ob_start();
        require_once($view);
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    public function redirect(string $url, $data = [])
    {
        session_start();
        $_SESSION['data'] = $data;
        header("Location: $url");
        exit();
    }

    public function json(array $data, int $http_response_code)
    {
        ob_start();
        header_remove();
        header('Content-type: application/json; charset=utf-8');
        http_response_code($http_response_code);
        echo json_encode($data);
        exit();
    }
}
