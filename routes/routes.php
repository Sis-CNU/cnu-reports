<?php

/**
 * 
 * 
 */
$routes = [
   "/" => [
      "controller" => "MainController",
      "callback" => "mainPage",
   ],
   "home" => [
      "controller" => "MainController",
      "callback" => "mainPage",
   ],
   "research" => [
      "post" => [
         "controller" => "MainController",
         "callback" => "getPostResearch",
      ],
      "under" => [
         "controller" => "MainController",
         "callback" => "getResearchUnder",
      ],
      ":id/all-events/category/:cat" => [
         "controller" => "MainController",
         "callback" => "getEvent",
         "params" => [
            "id" => "number",
            "cat" => "string"
         ],
      ],
      "events/:cat/category/:id" => [
         "controller" => "MainController",
         "callback" => "getEventTest",
         "params" => [
            "cat" => "string",
            "id" => "number"
         ],
      ],
      ":id" => [
         "controller" => "MainController",
         "callback" => "testOnlyOneParameter",
         "params" => [            
            "id" => "number"
         ],
      ],
   ],
   "events" => [],
   "courses" => []
];
