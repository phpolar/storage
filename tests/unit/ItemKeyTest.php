<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(ItemKey::class)]
final class ItemKeyTest extends TestCase
{
    #[TestDox("Shall return string representation of internal integer key")]
    public function test1()
    {
        $expected = "100";
        $givenKey = 100;
        $sut = new ItemKey($givenKey);
        $this->assertSame($expected, (string) $sut);
    }

    #[TestDox("Shall return internal string key")]
    public function test2()
    {
        $givenKey = uniqid();
        $sut = new ItemKey($givenKey);
        $this->assertSame($givenKey, (string) $sut);
    }
}
