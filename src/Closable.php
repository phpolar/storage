<?php

namespace Phpolar\Storage;

/**
 * Provides resource closing functionality.
 */
interface Closable
{
    /**
     * Cleanup resources.
     */
    public function close(): void;
}
