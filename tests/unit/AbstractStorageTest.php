<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use Phpolar\Phpolar\Storage\Tests\Fakes\FakeModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractStorage::class)]
#[CoversClass(ItemNotFound::class)]
#[CoversClass(Item::class)]
final class AbstractStorageTest extends TestCase
{
    protected function getStorageStub(): AbstractStorage
    {
        return new class() extends AbstractStorage {
            public  function commit(): void
            {
                // no op
            }
        };
    }
    #[TestDox("Shall allow for retrieving an item by key")]
    public function test1a()
    {

        $givenKey = new ItemKey(uniqid());
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $sut->storeByKey($givenKey, Item::unit($givenItem));
        $result = $sut->getByKey($givenKey);
        $this->assertInstanceOf(Item::class, $result);
        $storedItem = $result->bind();
        $this->assertObjectEquals($givenItem, $storedItem);
    }

    #[TestDox("Shall allow for retrieving an item by key")]
    public function test1b()
    {
        $givenKey = new ItemKey(uniqid());
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $result = $sut->getByKey($givenKey);
        $this->assertInstanceOf(ItemNotFound::class, $result);
    }

    #[TestDox("Shall allow for retrieve all items")]
    public function test2a()
    {
        $item1 = new FakeModel(uniqid(), uniqid());
        $item2 = new FakeModel(uniqid(), uniqid());
        $item3 = new FakeModel(uniqid(), uniqid());
        $item4 = new FakeModel(uniqid(), uniqid());
        $givenItems = [$item1, $item2, $item3, $item4];
        $key1 = new ItemKey(uniqid());
        $key2 = new ItemKey(random_int(PHP_INT_MIN, PHP_INT_MAX));
        $key3 = new ItemKey(uniqid());
        $key4 = new ItemKey(uniqid());
        $sut = $this->getStorageStub();
        $sut->storeByKey($key1, Item::unit($item1));
        $sut->storeByKey($key2, Item::unit($item2));
        $sut->storeByKey($key3, Item::unit($item3));
        $sut->storeByKey($key4, Item::unit($item4));
        $storedItems = $sut->getAll();
        array_map(
            $this->assertObjectEquals(...),
            $givenItems,
            $storedItems,
        );
    }

    #[TestDox("Shall return an empty array when no items are stored and getAll is called")]
    public function test2b()
    {
        $sut = $this->getStorageStub();
        $storedItems = $sut->getAll();
        $this->assertEmpty($storedItems);
    }

    #[TestDox("Shall allow for storing items")]
    public function test3()
    {
        $givenKey = new ItemKey(uniqid());
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $sut->storeByKey($givenKey, Item::unit($givenItem));
        $result = $sut->getByKey($givenKey);
        $this->assertInstanceOf(Item::class, $result);
        $storedItem = $result->bind();
        $this->assertObjectEquals($givenItem, $storedItem);
    }

    #[TestDox("Shall allow for removing an item by key")]
    public function test4()
    {
        $givenKey = new ItemKey(uniqid());
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $sut->storeByKey($givenKey, Item::unit($givenItem));
        $result = $sut->getByKey($givenKey);
        $this->assertInstanceOf(Item::class, $result);
        $storedItem = $result->bind();
        $this->assertObjectEquals($givenItem, $storedItem);
        $sut->removeByKey($givenKey);
        $result = $sut->getByKey($givenKey);
        $this->assertInstanceOf(ItemNotFound::class, $result);
    }

    #[TestDox("Shall allow for clearing all stored items")]
    public function test5()
    {
        $item1 = new FakeModel(uniqid(), uniqid());
        $item2 = new FakeModel(uniqid(), uniqid());
        $item3 = new FakeModel(uniqid(), uniqid());
        $item4 = new FakeModel(uniqid(), uniqid());
        $key1 = new ItemKey(uniqid());
        $key2 = new ItemKey(uniqid());
        $key3 = new ItemKey(uniqid());
        $key4 = new ItemKey(uniqid());
        $sut = $this->getStorageStub();
        $sut->storeByKey($key1, Item::unit($item1));
        $sut->storeByKey($key2, Item::unit($item2));
        $sut->storeByKey($key3, Item::unit($item3));
        $sut->storeByKey($key4, Item::unit($item4));
        $sut->clear();
        $storedItems = $sut->getAll();
        $this->assertEmpty($storedItems);
    }

    #[TestDox("Shall return the count of all stored items")]
    public function test6()
    {
        $item1 = new FakeModel(uniqid(), uniqid());
        $item2 = new FakeModel(uniqid(), uniqid());
        $item3 = new FakeModel(uniqid(), uniqid());
        $item4 = new FakeModel(uniqid(), uniqid());
        $expectedCount = count([$item1, $item2, $item3, $item4]);
        $key1 = new ItemKey(uniqid());
        $key2 = new ItemKey(uniqid());
        $key3 = new ItemKey(uniqid());
        $key4 = new ItemKey(uniqid());
        $sut = $this->getStorageStub();
        $sut->storeByKey($key1, Item::unit($item1));
        $sut->storeByKey($key2, Item::unit($item2));
        $sut->storeByKey($key3, Item::unit($item3));
        $sut->storeByKey($key4, Item::unit($item4));
        $result = $sut->getCount();
        $this->assertSame($expectedCount, $result);
    }

    #[TestDox("Shall allow for retrieving items by a key object with the same value as the original key")]
    public function test7()
    {
        $givenItem = new FakeModel();
        $keyAsString = uniqid();
        $originalKey = new ItemKey($keyAsString);
        $sameKey = new ItemKey($keyAsString);
        $sut = $this->getStorageStub();
        $sut->storeByKey($originalKey, Item::unit($givenItem));
        $result = $sut->getByKey($sameKey)->bind();
        $this->assertObjectEquals($givenItem, $result);
    }

    #[TestDox("Shall allow for replacing items by key")]
    public function test8()
    {
        $givenItem = new FakeModel(uniqid(), uniqid());
        $replacementItem = new FakeModel(uniqid(), uniqid());
        $keyAsString = uniqid();
        $key = new ItemKey($keyAsString);
        $sameKey = new ItemKey($keyAsString);
        $sut = $this->getStorageStub();
        $sut->storeByKey($key, Item::unit($givenItem));
        $stored = $sut->getByKey($key)->bind();
        $this->assertObjectEquals($givenItem, $stored);
        $sut->replaceByKey($sameKey, Item::unit($replacementItem));
        $replaced = $sut->getByKey($sameKey)->bind();
        $this->assertObjectEquals($replacementItem, $replaced);
    }

    #[TestDox("Shall allow for removing items by a key object with the same value as the original key")]
    public function test9()
    {
        $givenItem = new FakeModel();
        $keyAsString = uniqid();
        $originalKey = new ItemKey($keyAsString);
        $sameKey = new ItemKey($keyAsString);
        $sut = $this->getStorageStub();
        $sut->storeByKey($originalKey, Item::unit($givenItem));
        $sut->removeByKey($sameKey);
        $result = $sut->getByKey($sameKey);
        $this->assertInstanceOf(ItemNotFound::class, $result);
    }
}