<?php

namespace App;

enum HttpMethod: string
{
    case GET = "get";
    case POST = "post";
    case PUT = "put";
    case DELETE = "delete";
}