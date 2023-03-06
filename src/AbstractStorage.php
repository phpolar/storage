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

    /**
     * Stores keys by their string representation
     *
     * @var array<string,ItemKey> $keyMap
     */
    private array $keyMap = [];


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
     * Persists the data.
     *
     * This can mean commiting a set of records
     * to a database or saving data to a file.
     */
    public abstract function commit(): void;

    /**
     * Loads persisted data into the internal data
     * structure.
     */
    public abstract function load(): void;

    /**
     * Returns the key of an item.
     */
    public function findKey(Item $item): ItemKey|KeyNotFound
    {
        $itemValue = $item->bind();
        foreach ($this->map as $key => $storedItem) {
            $storedItemValue = $storedItem->bind();
            if (is_object($storedItemValue) === true) {
                if (method_exists($storedItemValue, "equals") === true) {
                    if ($storedItemValue->equals($itemValue) === true) {
                        return $key;
                    }
                    return new KeyNotFound();
                }
                $propsOfCurrent = get_object_vars($storedItemValue);
                $propsToCheck = get_object_vars($itemValue);
                if (count(array_intersect_assoc($propsOfCurrent, $propsToCheck)) === (count($propsOfCurrent) + count($propsToCheck)) / 2) {
                    return $key;
                }
                return new KeyNotFound();
            }
            if ($storedItemValue === $itemValue) {
                return $key;
            }
        }
        return new KeyNotFound();
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
        return $this->map[$this->keyMap[(string) $key] ?? $key] ?? new ItemNotFound();
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
        unset($this->map[$this->keyMap[(string) $key] ?? $key]);
        unset($this->keyMap[(string) $key]);
    }

    /**
     * Stores an item by key.
     */
    public function storeByKey(ItemKey $key, Item $item): void
    {
        $this->map[$key] = $item;
        $this->keyMap[(string) $key] = $key;
    }

    /**
     * Replaces an item by key.
     */
    public function replaceByKey(ItemKey $key, Item $item): void
    {
        $this->removeByKey($key);
        $this->map[$key] = $item;
    }
}
