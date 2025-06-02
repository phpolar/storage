<?php

namespace Phpolar\Storage;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(NotFound::class)]
#[CoversClass(InvalidQueryStateException::class)]
final class NotFoundTest extends TestCase
{
    #[TestDox("Shall return the return value of the alternative action if queried data does not exist")]
    public function testb()
    {
        $alternativeResult = PHP_INT_MAX;
        $res = new NotFound()->orElse(static fn() => $alternativeResult);
        $unwrappedValue = $res->tryUnwrap();
        $this->assertSame($unwrappedValue, $alternativeResult);
    }

    #[TestDox("Shall throw and exception when data is not found and an alternative action has not been configured")]
    public function testc()
    {
        $res = new NotFound();
        $this->expectException(InvalidQueryStateException::class);
        $res->tryUnwrap();
    }
}
