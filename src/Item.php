<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

/**
 * Represents the scenario when an item was retrieved from storage.
 */
final class Item
{
    public function __construct(private readonly mixed $item)
    {
    }

    /**
     * Retrieves the underlying type
     */
    public function bind(): mixed
    {
        return $this->item;
    }
}
