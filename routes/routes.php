<?php

$routes = [
   "" => [
      "controller" => "MainController",
      "callback" => "mainPage",
      "params" => [],
   ],
   "/this/is/my/path" => [
      "controller" => "MainController",
      "callback" => "link",
      "params" => [],
   ],
   "nueva-ruta" => [
      "controller" => "MainController",
      "callback" => "newPath",
      "params" => [],
   ],
   "article/create" => [
      "controller" => "ArticleController",
      "callback" => "create",
      "params" => [],
   ],
   "article/:id" => [
      "controller" => "ArticleController",
      "callback" => "getArticle",
      "params" => [
         "id"=> "number" 
      ],
   ],
   "test-get" => [
      "controller" => "MainController",
      "callback" => "test_get",
      "params" => [],
   ],
   "latex-page" => [
      "controller" => "MainController",
      "callback" => "latex",
      "params" => [],
   ],
   "pdf" => [
      "controller" => "MainController",
      "callback" => "pdf",
      "params" => [],
   ],
   "execute" => [
      "controller" => "MainController",
      "callback" => "execute",
      "params" => [],
   ],
];
