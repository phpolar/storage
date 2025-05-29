<?php

namespace Phpolar\Storage;

/**
 * Encapsulates hooks that will
 * be called when a class is
 * constructed.
 */
interface InitHook
{
    /**
     * Calls the attached hooks when the class
     * is initialized
     */
    public function onInit(): void;
}
