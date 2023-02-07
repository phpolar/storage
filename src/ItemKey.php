<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use Stringable;

/**
 * Represents the key used to locate an item.
 */
final class ItemKey implements Stringable
{
    public function __construct(private string|int $key)
    {
    }

    public function __toString(): string
    {
        return (string) $this->key;
    }
}
