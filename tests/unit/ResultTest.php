<?php

namespace Phpolar\Storage;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(Result::class)]
#[CoversClass(InvalidQueryStateException::class)]
final class ResultTest extends TestCase
{
    #[TestDox("Shall provide the queried data if it exists in the storage context")]
    public function testa()
    {
        $existingValue = PHP_INT_MIN;
        $res = new Result($existingValue);
        $unwrappedValue = $res->tryUnwrap();
        $this->assertSame($existingValue, $unwrappedValue);
    }

    #[TestDox("Shall not use alternative action when data is found")]
    public function testf()
    {
        $existingValue = PHP_INT_MIN;
        $alternativeActionResult = "SHOULD_NOT_RETURN";
        $res = new Result($existingValue)->orElse(static fn() => $alternativeActionResult);
        $unwrappedValue = $res->tryUnwrap();
        $this->assertNotSame($unwrappedValue, $alternativeActionResult);
    }
}
