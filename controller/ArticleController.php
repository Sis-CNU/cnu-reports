<?php

namespace Controller;

use Model\Article;
// use Controller\Response;
use Controller\Artefacts;
use Controller\Request;

class ArticleController implements Artefacts
{
    use Singleton;

    public function index()
    {
        return ['success' => 'Articles page has been indexed successfully'];
    }

    public function create()
    {
        // $request = new Request();
        // $response = new  Response();

        // $article = new Article([
        //     "name"  => $request->getString('name'),
        //     "brand" => $request->getString('brand'),
        //     "price" => $request->getNumber('price'),
        //     "stock" => $request->getInt('stock')
        // ]);

        // if ($article->create()) {
        //     return $response->redirect("/test-get", [
        //         'success' => 'Data added successfully',
        //         'data' => $article->getParams()
        //     ]);
        // }                
    }

    public function test_get()
    {
        // $response = new Response();
        // return $response->view('../view/page-one.php');
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
        // $response = new Response();
        // $article = new Article();
        // return $response->json($article->getArticle([
        //     'id' => $id
        // ]), 200);
    }
}
