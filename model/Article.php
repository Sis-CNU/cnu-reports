<?php

declare(strict_types=1);

namespace Model;

use Controller\Database;

class Article
{
    protected array $params;

    public function __construct(array $paramsBase = [])
    {
        $this->params = $paramsBase;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function create()
    {
        return Database::execute(
            "CALL sp_new_article(:name, :brand, :price, :stock)",
            $this->getParams()
        );
    }

    public function getArticle(array $articleParams)
    {
        return Database::fetch(
            "SELECT * FROM articles_second_proposal WHERE id = :id",
            $articleParams
        );
    }

    public function getAll()
    {
        return Database::fetch(
            "SELECT * FROM articles_second_proposal"
        );
    }
}
