<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

/**
 * Represents the scenario when an item was retrieved from storage.
 */
final class Item
{
    private function __construct(private mixed $item)
    {
    }

    /**
     * Wraps an item.
     */
    public static function unit(mixed $item): Item
    {
        return new self($item);
    }

    /**
     * Retrieves the underlying type
     */
    public function bind(): mixed
    {
        return $this->item;
    }
}
