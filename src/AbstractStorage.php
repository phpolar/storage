<?php

declare(strict_types=1);

namespace Phpolar\Storage;

use Countable;

/**
 * Represents a persistence layer.
 *
 * @implements StorageContext<mixed>
 */
abstract class AbstractStorage implements StorageContext, Countable
{
    /**
     * Internal storage data structure.
     *
     * @var array<string|int,mixed> $map
     */
    private array $map = [];

    public function __construct(private readonly InitHook & DestroyHook $hooks)
    {
        $this->hooks->onInit();
    }

    public function __destruct()
    {
        $this->hooks->onDestroy();
    }

    /**
     * Clears all stored items.
     */
    public function clear(): void
    {
        $this->map = [];
    }

    /**
     * Returns the key of an item.
     */
    public function findKey(mixed $item): string|int|KeyNotFound
    {
        foreach ($this->map as $key => $storedItem) {
            if (is_object($storedItem) === true && is_object($item) === true) {
                $propsOfCurrent = get_object_vars($storedItem);
                $propsToCheck = get_object_vars($item);
                if (count(array_intersect_assoc($propsOfCurrent, $propsToCheck)) === (count($propsOfCurrent) + count($propsToCheck)) / 2) {
                    return $key;
                }
                return new KeyNotFound();
            }
            if ($storedItem === $item) {
                return $key;
            }
        }
        return new KeyNotFound();
    }

    /**
     * Returns the number of all stored items.
     */
    public function count(): int
    {
        return count($this->map);
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD)
     */
    public function find(string|int $key): Queryable
    {
        if (array_key_exists($key, $this->map) === false) {
            return new NotFound();
        }
        return new Result($this->map[$key]);
    }

    public function findAll(): array
    {
        $arr = [];
        foreach ($this->map as $item) {
            $arr[] = $item;
        }
        return $arr;
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD)
     */
    public function remove(string|int $key): Queryable
    {
        if (array_key_exists($key, $this->map) === false) {
            return new NotFound();
        }
        $result = new Result($this->map[$key]);
        unset($this->map[$key]);
        return $result;
    }

    public function save(string|int $key, mixed $data): void
    {
        $this->map[$key] = $data;
    }

    public function replace(string|int $key, mixed $data): mixed
    {
        $this->remove($key);
        $this->save($key, $data);
        return $data;
    }
}
