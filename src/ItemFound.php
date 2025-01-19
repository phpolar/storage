<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use RuntimeException;

/**
 * Represents the scenario when an item is found.
 */
final class ItemFound
{
    public function __construct(private readonly Item $item) {}

    public function bind(): mixed
    {
        return $this->item->bind();
    }
}
