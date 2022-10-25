<?php

namespace Controller;

/**
 * Clase MainController
 */
class MainController
{
    // Uso de patrón de diseño Singleton.
    use Singleton;

    /**
     * Variable Response
     *
     * @var Response
     */
    private static Response $response;

    private function __construct()
    {
        self::$response = Response::getInstance();
    }

    public function index()
    {             
        return self::$response->view('../view/app.php');        
    }
}
