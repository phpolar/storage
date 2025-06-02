<?php

namespace Phpolar\Storage;

use Closure;

final class NotFound implements Queryable
{
    private Closure $alternativeAction;
    private bool $hasAlternativeAction = false;

    public function __construct()
    {
        $this->alternativeAction = static function () {
            // intentionally empty
        };
    }

    public function tryUnwrap(): mixed
    {
        if ($this->hasInvalidQueryState() === true) {
            throw new InvalidQueryStateException();
        }
        $alternativeAction = $this->alternativeAction;
        return $alternativeAction();
    }

    public function orElse(?Closure $alternativeAction = null): self
    {
        $this->hasAlternativeAction = true;
        $this->alternativeAction = $alternativeAction ?? static function () {
            // intentionally empty
        };
        return $this;
    }

    private function hasInvalidQueryState(): bool
    {
        return $this->hasAlternativeAction === false;
    }
}
