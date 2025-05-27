<?php

namespace Phpolar\Storage;

/**
 * Encapsulates hooks that will
 * be called at life cylce intervals
 */
interface LifeCycleHooks
{
    /**
     * Calls the attached hooks when the storage
     * is initialized
     */
    public function onInit(): void;

    /**
     * Calls the attached hooks when the storage
     * is destroyed
     */
    public function onDestroy(): void;
}
