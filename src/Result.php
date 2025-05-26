<?php

namespace Phpolar\Phpolar\Storage;

use Closure;

/**
 * Represents a query for data that may or may not exist
 * in the storage context.
 *
 * @template-covariant T
 */
final class Result
{
    private bool $hasFoundData = false;
    private bool $hasAlternativeAction = false;

    /**
     * @param ?T $data
     */
    private function __construct(private $data = null) {}

    /**
     * @return Result<null>
     */
    public static function notFound(): self
    {
        return new self();
    }

    /**
     * Configures an alternative action that provides
     * the value that should be returned when
     * the queried data does not exist in storage.
     *
     * @return Result<T>
     */
    public function orElse(Closure $alternativeAction): self
    {
        $this->hasAlternativeAction = true;
        $this->data = $alternativeAction();
        return $this;
    }

    /**
     * One of three behaviors depending on the internal state:
     *   1. Provide the value if it exists
     *   2. Provide the configured alternative value if the queried value does not exist.
     *   3. Throws an exception when the value does not exist and an alternative has not
     *      configured.
     * @throws InvalidQueryStateException
     * @return ?T
     */
    public function tryUnwrap()
    {
        if ($this->hasInvalidQueryState() === true) {
            throw new InvalidQueryStateException();
        }
        return $this->data;
    }

    /**
     * Create a result context representing
     * data found in storage.
     *
     * @param mixed $val
     * @return Result<mixed>
     */
    public static function wrap(mixed $val): self
    {
        $wrapped = new self($val);
        $wrapped->hasFoundData = true;
        return $wrapped;
    }

    private function hasInvalidQueryState(): bool
    {
        return $this->hasFoundData === false
            && $this->hasAlternativeAction === false;
    }
}
