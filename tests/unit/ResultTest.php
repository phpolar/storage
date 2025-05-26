<?php

namespace Phpolar\Phpolar\Storage;

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
        $res = Result::wrap($existingValue);
        $unwrappedValue = $res->tryUnwrap();
        $this->assertSame($existingValue, $unwrappedValue);
    }

    #[TestDox("Shall return the return value of the alternative action if queried data does not exist")]
    public function testb()
    {
        $alternativeResult = PHP_INT_MAX;
        $res = Result::notFound()
            ->orElse(
                static fn() => $alternativeResult
            );
        $unwrappedValue = $res->tryUnwrap();
        $this->assertSame($unwrappedValue, $alternativeResult);
    }

    #[TestDox("Shall throw and exception when data is not found and an alternative action has not been configured")]
    public function testc()
    {
        $res = Result::notFound();
        $this->expectException(InvalidQueryStateException::class);
        $res->tryUnwrap();
    }

    #[TestDox("Shall not reuse previously wrapped data")]
    public function testd()
    {
        $existingValue = PHP_INT_MIN;
        $res = Result::notFound();
        $res::wrap($existingValue);
        $this->expectException(InvalidQueryStateException::class);
        $res->tryUnwrap();
    }

    #[TestDox("Shall create new instance with new query state when calling wrap on 'not found' result")]
    public function teste()
    {
        $existingValue = PHP_INT_MIN;
        $res = Result::notFound();
        $newResult = $res::wrap($existingValue);
        $unwrappedValue = $newResult->tryUnwrap();
        $this->assertSame($existingValue, $unwrappedValue);
    }
}
