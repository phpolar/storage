<?php

namespace Phpolar\Storage;

use Closure;

/**
 * Represents a query for data that may or may not exist
 * in the storage context.
 */
final class Result implements Queryable
{
    /**
     * @param mixed $data
     */
    public function __construct(private $data) {}

    /**
     * Configures an alternative action that provides
     * the value that should be returned when
     * the queried data does not exist in storage.
     */
    public function orElse(?Closure $alternativeAction = null): self
    {
        // intentionally empty
        // no need to invoke an alternative action
        return $this;
    }

    public function tryUnwrap(): mixed
    {
        return $this->data;
    }
}
