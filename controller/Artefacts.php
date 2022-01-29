<?php

declare(strict_types=1);

namespace Controller;

interface Artefacts
{
    public static function index();

    public static function create();

    public static function read(int $id);

    public static function update(int $id);

    public static function delete(int $id);
}
