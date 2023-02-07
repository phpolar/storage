<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use WeakMap;

/**
 * Represents a persistence layer.
 */
abstract class AbstractStorage
{
    /**
     * Internal storage data structure.
     *
     * @var WeakMap<ItemKey,Item> $map
     */
    private WeakMap $map;

    public function __construct()
    {
        $this->map = new WeakMap();
    }

    /**
     * Clears all stored items.
     */
    public function clear(): void
    {
        $this->map = new WeakMap();
    }

    /**
     * Returns the number of all stored items.
     */
    public function getCount(): int
    {
        return count($this->map);
    }

    /**
     * Retrieves an item by key.
     */
    public function getByKey(ItemKey $key): Item|ItemNotFound
    {
        return $this->map[$key] ?? new ItemNotFound();
    }

    /**
     * Retrieves all stored items.
     *
     * @return mixed[]
     */
    public function getAll(): array
    {
        $arr = [];
        foreach ($this->map as $item) {
            $arr[] = $item->bind();
        }
        return $arr;
    }

    /**
     * Removes an item associated with the given key.
     */
    public function removeByKey(ItemKey $key): void
    {
        unset($this->map[$key]);
    }

    /**
     * Stores an item by key.
     */
    public function storeByKey(ItemKey $key, Item $item): void
    {
        $this->map[$key] = $item;
    }
}
