<?php

namespace Phpolar\Storage;

/**
 * Represents a context where data can be stored
 * and modified.
 *
 * @template-covariant T
 */
interface StorageContext
{
    /**
     * Attempt to locate data within the context.
     *
     * @return Queryable
     */
    public function find(string|int $key): Queryable;

    /**
     * Return all items of like type.
     *
     * @return T[]
     */
    public function findAll(): array;

    /**
     * Attempt to remove data from the context.
     *
     * @return Queryable
     */
    public function remove(string|int $key): Queryable;

    /**
     * Add data to the context.
     */
    public function save(string|int $key, mixed $data): void;

    /**
     * Replace data within the context.
     *
     * @return T
     */
    public function replace(string|int $key, mixed $data): mixed;
}
