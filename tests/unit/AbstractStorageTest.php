<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use Phpolar\Phpolar\Storage\Tests\Fakes\FakeModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractStorage::class)]
#[CoversClass(Item::class)]
#[CoversClass(ItemNotFound::class)]
#[CoversClass(KeyNotFound::class)]
final class AbstractStorageTest extends TestCase
{
    protected function getStorageStub(): AbstractStorage
    {
        return new class() extends AbstractStorage {
            public  function commit(): void
            {
                // no op
            }

            protected  function load(): void
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
        $sut->storeByKey($givenKey, new Item($givenItem));
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

    #[TestDox("Shall allow for retrieving all items")]
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
        $sut->storeByKey($key1, new Item($item1));
        $sut->storeByKey($key2, new Item($item2));
        $sut->storeByKey($key3, new Item($item3));
        $sut->storeByKey($key4, new Item($item4));
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
        $sut->storeByKey($givenKey, new Item($givenItem));
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
        $sut->storeByKey($givenKey, new Item($givenItem));
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
        $sut->storeByKey($key1, new Item($item1));
        $sut->storeByKey($key2, new Item($item2));
        $sut->storeByKey($key3, new Item($item3));
        $sut->storeByKey($key4, new Item($item4));
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
        $sut->storeByKey($key1, new Item($item1));
        $sut->storeByKey($key2, new Item($item2));
        $sut->storeByKey($key3, new Item($item3));
        $sut->storeByKey($key4, new Item($item4));
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
        $sut->storeByKey($originalKey, new Item($givenItem));
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
        $sut->storeByKey($key, new Item($givenItem));
        $stored = $sut->getByKey($key)->bind();
        $this->assertObjectEquals($givenItem, $stored);
        $sut->replaceByKey($sameKey, new Item($replacementItem));
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
        $sut->storeByKey($originalKey, new Item($givenItem));
        $sut->removeByKey($sameKey);
        $result = $sut->getByKey($sameKey);
        $this->assertInstanceOf(ItemNotFound::class, $result);
    }

    #[TestDox("Shall return the key of a stored object")]
    public function test10()
    {
        $givenItem = new Item(new FakeModel());
        $keyAsString = uniqid();
        $givenKey = new ItemKey($keyAsString);
        $sut = $this->getStorageStub();
        $sut->storeByKey($givenKey, $givenItem);
        $result = $sut->findKey($givenItem);
        $this->assertSame($keyAsString, (string) $result);
    }

    #[TestDox("Shall return the key of a stored object that does not implement the equals method")]
    #[Group("me")]
    public function test11()
    {
        $givenItem = new Item((object) ["name" => "eric"]);
        $keyAsString = uniqid();
        $givenKey = new ItemKey($keyAsString);
        $sut = $this->getStorageStub();
        $sut->storeByKey($givenKey, $givenItem);
        $result = $sut->findKey($givenItem);
        $this->assertSame($keyAsString, (string) $result);
    }

    #[TestDox("Shall return the key of a stored scalar value")]
    public function test12()
    {
        $givenItem = new Item(2 ** 44);
        $keyAsString = uniqid();
        $givenKey = new ItemKey($keyAsString);
        $sut = $this->getStorageStub();
        $sut->storeByKey($givenKey, $givenItem);
        $result = $sut->findKey($givenItem);
        $this->assertSame($keyAsString, (string) $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a given item does not exist in storage")]
    public function test13()
    {
        $givenItem = new Item(new FakeModel());
        $sut = $this->getStorageStub();
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when an object that does not implement the equals method does not exist in storage")]
    public function test14()
    {
        $givenItem = new Item((object) ["name" => "eric"]);
        $sut = $this->getStorageStub();
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a scalar value does not exist in storage")]
    public function test15()
    {
        $givenItem = new Item(2 ** 44);
        $sut = $this->getStorageStub();
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a given item does not exist in storage")]
    public function test13b()
    {
        $anotherItemKey = new ItemKey(uniqid());
        $givenItem = new Item(new FakeModel());
        $anotherItem = new Item(new FakeModel("another", "item"));
        $sut = $this->getStorageStub();
        $sut->storeByKey($anotherItemKey, $anotherItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when an object that does not implement the equals method does not exist in storage")]
    public function test14b()
    {
        $anotherItemKey = new ItemKey(uniqid());
        $givenItem = new Item((object) ["name" => "eric"]);
        $anotherItem = new Item((object) ["name" => "someone else"]);
        $sut = $this->getStorageStub();
        $sut->storeByKey($anotherItemKey, $anotherItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a scalar value does not exist in storage")]
    public function test15b()
    {
        $anotherItemKey = new ItemKey(uniqid());
        $anotherItem = new Item(2 ** 43);
        $givenItem = new Item(2 ** 44);
        $sut = $this->getStorageStub();
        $sut->storeByKey($anotherItemKey, $anotherItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when there are no values in storage")]
    public function testa()
    {
        $sut = $this->getStorageStub();
        $givenItem = new Item(2 ** 44);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item is empty")]
    public function testb()
    {
        $key = new ItemKey(uniqid());
        $sut = $this->getStorageStub();
        $storedItem = new Item((object) []);
        $givenItem = new Item((object) ["other" => "values"]);
        $sut->storeByKey($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item and given item do not have the same number of properties")]
    public function testc()
    {
        $key = new ItemKey(uniqid());
        $sut = $this->getStorageStub();
        $storedItem = new Item((object) ["a" => "prop"]);
        $givenItem = new Item((object) ["a" => "prop", "another" => "prop"]);
        $sut->storeByKey($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item and given item have the same values but different property names")]
    public function testd()
    {
        $key = new ItemKey(uniqid());
        $sut = $this->getStorageStub();
        $storedItem = new Item((object) ["a" => "prop"]);
        $givenItem = new Item((object) ["b" => "prop"]);
        $sut->storeByKey($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }
}
