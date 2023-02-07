<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use RuntimeException;

/**
 * Represents the scenario when an item is not found.
 */
final class ItemNotFound
{
    /**
     * This method exists to unit this type
     * with the Item type.
     *
     * An exception will be thrown if this method is called.
     * @throws RuntimeException
     */
    public function bind(): never
    {
        throw new RuntimeException("The item was not found. There is nothing to retrieve.");
    }
}
