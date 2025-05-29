<?php

namespace Phpolar\Storage;

/**
 * Supports persisting data into a storage context.
 */
interface Persistable
{
    /**
     * Persist data into a storage context.
     */
    public function persist(): void;
}
