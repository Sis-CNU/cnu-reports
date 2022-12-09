<?php

declare(strict_types=1);

namespace Controller;

use App\Request;
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
     * Variable Request
     * 
     * @var Request
     */
    private static Request $request;

    /**
     * Variable Response
     *
     * @var Response
     */
    private static Response $response;

    private function __construct()
    {
        self::$request = Request::getInstance();
        self::$response = Response::getInstance();
    }

    public function index(): mixed
    {
        return self::$response->view('../view/index.php');
    }

    public function create(): mixed
    {
        return self::$response->json([
            "data" => self::$request->getAll(),
            "specific_data" => self::$request->getData("param1"),
            "success" => "post"
        ], 200);
    }

    public function show(int | string $id): mixed
    {
        # code...
    }

    public function update(int | string $id): mixed
    {
        return self::$response->json(["success" => "update $id"], 200);
    }

    public function delete(int | string $id): mixed
    {
        return self::$response->json(["success" => "delete $id"], 200);
    }

    public function tasks(int $id)
    {
        return "<pre>Vista de tarea $id</pre>";
    }

    public function getTasks()
    {
        return self::$response->json(["success" => "get all tasks"], 200);
    }

    public function users(int $id)
    {
        return "<pre>Vista de usuario $id</pre>";
    }

    public function about()
    {
        return "<pre>Vista de acerca de nosotros</pre>";
    }
}
