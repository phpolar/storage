<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use Phpolar\Phpolar\Storage\Tests\Fakes\FakeModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use WeakMap;

#[CoversClass(AbstractStorage::class)]
#[CoversClass(ItemNotFound::class)]
#[CoversClass(Item::class)]
final class AbstractStorageTest extends TestCase
{
    #[TestDox("Shall allow for retrieving an item by key")]
    public function test1a()
    {

        $givenKey = new ItemKey(uniqid());
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = new class() extends AbstractStorage {};
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
        $map = new WeakMap();
        $sut = new class($map) extends AbstractStorage {};
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
        $sut = new class() extends AbstractStorage {};
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
        $sut = new class() extends AbstractStorage {};
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
        $sut = new class() extends AbstractStorage {};
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
        $sut = new class() extends AbstractStorage {};
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
        $sut = new class() extends AbstractStorage {};
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
        $sut = new class() extends AbstractStorage {};
        $sut->storeByKey($key1, Item::unit($item1));
        $sut->storeByKey($key2, Item::unit($item2));
        $sut->storeByKey($key3, Item::unit($item3));
        $sut->storeByKey($key4, Item::unit($item4));
        $result = $sut->getCount();
        $this->assertSame($expectedCount, $result);
    }
}
