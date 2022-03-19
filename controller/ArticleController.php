<?php

namespace Controller;

use Model\Article;
use Controller\Artefacts;

/**
 * Clase ArticleController
 */
class ArticleController implements Artefacts
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
     * * Variable Response
     *
     * @var Response
     */
    private static Response $response;

    public function index()
    {
        return ['success' => 'Articles page has been indexed successfully'];
    }

    public function create()
    {
        self::$request = Request::getInstance();
        self::$response = Response::getInstance();

        $article = new Article([
            "name"  => self::$request->getString('name'),
            "brand" => self::$request->getString('brand'),
            "price" => self::$request->getNumber('price'),
            "stock" => self::$request->getInt('stock')
        ]);

        if ($article->create()) {
            return self::$response->redirect("/test-get", [
                'success' => 'Data added successfully',
                'data' => $article->getParams()
            ]);
        }
    }

    public function read(int $id)
    {
        return ['success' => 'Article has been readed successfully'];
    }

    public function update(int $id)
    {
        return ['success' => 'Article has been updated successfully'];
    }

    public function delete(int $id)
    {
        return ['success' => 'Article has been deleted successfully'];
    }

    public function getArticle(int $id)
    {
        self::$response = Response::getInstance();
        $article = new Article();

        return self::$response
            ->json($article->getArticle([
                'id' => $id
            ]), 200);
    }
}
