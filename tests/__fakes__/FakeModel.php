<?php

declare(strict_types=1);

namespace Phpolar\Storage\Tests\Fakes;

final class FakeModel
{
    public function __construct(public string $title = "Add a fake model", public string $myInput = "what") {}

    public function equals(self $other): bool
    {
        return $this->title === $other->title &&
            $this->myInput === $other->myInput;
    }
}
