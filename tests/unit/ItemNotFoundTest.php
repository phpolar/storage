<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(ItemNotFound::class)]
final class ItemNotFoundTest extends TestCase
{
    #[TestDox("Shall throw an exception when bind is called")]
    public function test1()
    {
        $this->expectException(RuntimeException::class);
        $sut = new ItemNotFound();
        $sut->bind();
    }
}
