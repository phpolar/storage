<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use Phpolar\Phpolar\Storage\Tests\Fakes\FakeModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    #[TestDox("Shall allow for retrieving the wrapped instance")]
    public function test1()
    {
        $item = new FakeModel();
        $wrapped = new Item($item);
        $this->assertObjectEquals($item, $wrapped->bind());
    }
}
