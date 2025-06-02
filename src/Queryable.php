<?php

namespace Phpolar\Storage;

use Closure;

interface Queryable
{
    /**
     * Configures an alternative action that provides
     * the value that should be returned when
     * the queried data does not exist in storage.
     */
    public function orElse(?Closure $alternativeAction): Queryable;

    /**
     * One of three behaviors depending on the internal state:
     *   1. Provide the value if it exists
     *   2. Provide the configured alternative value if the queried value does not exist.
     *   3. Throws an exception when the value does not exist and an alternative has not
     *      configured.
     * @throws InvalidQueryStateException
     */
    public function tryUnwrap(): mixed;
}
