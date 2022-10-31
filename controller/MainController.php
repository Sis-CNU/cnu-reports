<?php

declare(strict_types=1);

namespace Controller;

use App\Response;
use App\Singleton;
use Interface\Artefacts;


/**
 * Clase MainController
 */
class MainController implements Artefacts
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

    public function index(): mixed
    {
        return self::$response->view('../view/index.php');
    }

    public function create(): mixed
    {
        # code...
    }

    public function show(int | string $id): mixed
    {
        # code...
    }

    public function update(int | string $id): mixed
    {
        # code...
    }

    public function delete(int | string $id): mixed
    {
        # code...
    }
}
