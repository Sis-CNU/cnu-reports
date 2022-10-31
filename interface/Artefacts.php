<?php

declare(strict_types=1);

namespace Interface;

/**
 * Interface Artefacts
 */
interface Artefacts
{
    /**
     * Recupera todos los recursos de una tabla dentro de la base de datos.
     *
     * @return mixed
     * 
     */
    public function index(): mixed;

    /**
     * Crea un recurso dentro de la base de datos.
     *
     * @return mixed
     * 
     */
    public function create(): mixed;

    /**
     * Recupera un recurso según identificador.
     *
     * @param int $id
     * 
     * @return mixed
     * 
     */
    public function show(int | string $id): mixed;

    /**
     * Actualiza un recurso de la base de datos.
     *
     * @param int $id
     * 
     * @return mixed
     * 
     */
    public function update(int | string $id): mixed;

    /**
     * Actualiza el recurso a su estado 'Inhabilitado'
     *
     * @param int $id
     * 
     * @return mixed
     * 
     */
    public function delete(int | string $id): mixed;
}
