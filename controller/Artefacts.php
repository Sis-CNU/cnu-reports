<?php

declare(strict_types=1);

namespace Controller;

interface Artefacts
{
    public function index();

    public function create();

    public function read(int $id);

    public function update(int $id);

    public function delete(int $id);
}
