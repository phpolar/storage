<?php

namespace Phpolar\Storage;

/**
 * Encapsulates hooks that will
 * be called during a class's
 * destructure phase
 */
interface DestroyHook
{
    /**
     * Calls the attached hooks when the storage
     * is destroyed
     */
    public function onDestroy(): void;
}
