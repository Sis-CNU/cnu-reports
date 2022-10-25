<?php

$routes = [
   "/" => [
      "controller" => "MainController",
      "callback" => "index",
   ],

   "resource" => [
      "/" => [
         "controller" => "MainController",
         "callback" => "getFunction",
      ],

      "/:id" => [
         "controller" => "MainController",
         "callback" => "postFunction",
      ],
   ],
];
