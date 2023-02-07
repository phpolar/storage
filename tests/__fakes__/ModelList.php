<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage\Tests\Fakes;

final class ModelList
{
    public string $title = "FAKE LIST";

    public string $successMsg = "You did it!";

    /**
     * @var FakeModel[]
     */
    private array $items = [];

    public function add(FakeModel $model)
    {
        $this->items[] = $model;
    }

    /**
     * @return FakeModel[]
     */
    public function list(): array
    {
        return $this->items;
    }
}
