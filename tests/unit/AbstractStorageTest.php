<?php

declare(strict_types=1);

namespace Phpolar\Phpolar\Storage;

use Phpolar\Phpolar\Storage\LifeCycleHooks;
use Phpolar\Phpolar\Storage\Tests\Fakes\FakeModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractStorage::class)]
#[CoversClass(Result::class)]
#[CoversClass(KeyNotFound::class)]
final class AbstractStorageTest extends TestCase
{
    protected function getStorageStub(): AbstractStorage
    {
        $lifeCycleHooksStub = $this->createStub(LifeCycleHooks::class);
        return new class($lifeCycleHooksStub) extends AbstractStorage {};
    }
    #[TestDox("Shall allow for retrieving an item by key")]
    public function test1a()
    {

        $givenKey = uniqid();
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $sut->save($givenKey, $givenItem);
        $result = $sut->find($givenKey);
        $storedItem = $result->tryUnwrap();
        $this->assertObjectEquals($givenItem, $storedItem);
    }

    #[TestDox("Shall handle scenario when data is not in the storage context")]
    public function test1b()
    {
        $givenKey = uniqid();
        $sut = $this->getStorageStub();
        $result = $sut->find($givenKey);
        $this->expectException(InvalidQueryStateException::class);
        $result->tryUnwrap();
    }

    #[TestDox("Shall allow for retrieving all items")]
    public function test2a()
    {
        $item1 = new FakeModel(uniqid(), uniqid());
        $item2 = new FakeModel(uniqid(), uniqid());
        $item3 = new FakeModel(uniqid(), uniqid());
        $item4 = new FakeModel(uniqid(), uniqid());
        $givenItems = [$item1, $item2, $item3, $item4];
        $key1 = uniqid();
        $key2 = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $key3 = uniqid();
        $key4 = uniqid();
        $sut = $this->getStorageStub();
        $sut->save($key1, $item1);
        $sut->save($key2, $item2);
        $sut->save($key3, $item3);
        $sut->save($key4, $item4);
        $storedItems = $sut->findAll();
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
        $storedItems = $sut->findAll();
        $this->assertEmpty($storedItems);
    }

    #[TestDox("Shall allow for storing items")]
    public function test3()
    {
        $givenKey = uniqid();
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $sut->save($givenKey, $givenItem);
        $result = $sut->find($givenKey);
        $storedItem = $result->tryUnwrap();
        $this->assertObjectEquals($givenItem, $storedItem);
    }

    #[TestDox("Shall allow for removing an item by key")]
    public function test4()
    {
        $givenKey = uniqid();
        $givenItem = new FakeModel();
        $givenItem->title = "TITLE";
        $givenItem->myInput = "something";
        $sut = $this->getStorageStub();
        $sut->save($givenKey, $givenItem);
        $result = $sut->find($givenKey);
        $storedItem = $result->tryUnwrap();
        $this->assertObjectEquals($givenItem, $storedItem);
        $sut->remove($givenKey);
        $result = $sut->find($givenKey);
        $this->expectException(InvalidQueryStateException::class);
        $result->tryUnwrap();
    }

    #[TestDox("Shall allow for clearing all stored items")]
    public function test5()
    {
        $item1 = new FakeModel(uniqid(), uniqid());
        $item2 = new FakeModel(uniqid(), uniqid());
        $item3 = new FakeModel(uniqid(), uniqid());
        $item4 = new FakeModel(uniqid(), uniqid());
        $key1 = uniqid();
        $key2 = uniqid();
        $key3 = uniqid();
        $key4 = uniqid();
        $sut = $this->getStorageStub();
        $sut->save($key1, $item1);
        $sut->save($key2, $item2);
        $sut->save($key3, $item3);
        $sut->save($key4, $item4);
        $sut->clear();
        $storedItems = $sut->findAll();
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
        $key1 = uniqid();
        $key2 = uniqid();
        $key3 = uniqid();
        $key4 = uniqid();
        $sut = $this->getStorageStub();
        $sut->save($key1, $item1);
        $sut->save($key2, $item2);
        $sut->save($key3, $item3);
        $sut->save($key4, $item4);
        $result = count($sut);
        $this->assertSame($expectedCount, $result);
    }

    #[TestDox("Shall allow for replacing items by key")]
    public function test8()
    {
        $givenItem = new FakeModel(uniqid(), uniqid());
        $replacementItem = new FakeModel(uniqid(), uniqid());
        $keyAsString = uniqid();
        $key = $keyAsString;
        $sameKey = $keyAsString;
        $sut = $this->getStorageStub();
        $sut->save($key, $givenItem);
        $stored = $sut->find($key)->tryUnwrap();
        $this->assertObjectEquals($givenItem, $stored);
        $sut->replace($sameKey, $replacementItem);
        $replaced = $sut->find($sameKey)->tryUnwrap();
        $this->assertObjectEquals($replacementItem, $replaced);
    }

    #[TestDox("Shall allow for removing items by a key object with the same value as the original key")]
    public function test9()
    {
        $givenItem = new FakeModel();
        $keyAsString = uniqid();
        $originalKey = $keyAsString;
        $sameKey = $keyAsString;
        $sut = $this->getStorageStub();
        $sut->save($originalKey, $givenItem);
        $sut->remove($sameKey);
        $result = $sut->find($sameKey);
        $this->expectException(InvalidQueryStateException::class);
        $result->tryUnwrap();
    }

    #[TestDox("Shall return the key of a stored object")]
    public function test10()
    {
        $givenItem = new FakeModel();
        $keyAsString = uniqid();
        $givenKey = $keyAsString;
        $sut = $this->getStorageStub();
        $sut->save($givenKey, $givenItem);
        $result = $sut->findKey($givenItem);
        $this->assertSame($keyAsString, (string) $result);
    }

    #[TestDox("Shall return the key of a stored object that does not implement the equals method")]
    #[Group("me")]
    public function test11()
    {
        $givenItem = (object) ["name" => "eric"];
        $keyAsString = uniqid();
        $givenKey = $keyAsString;
        $sut = $this->getStorageStub();
        $sut->save($givenKey, $givenItem);
        $result = $sut->findKey($givenItem);
        $this->assertSame($keyAsString, (string) $result);
    }

    #[TestDox("Shall return the key of a stored scalar value")]
    public function test12()
    {
        $givenItem = 2 ** 44;
        $keyAsString = uniqid();
        $givenKey = $keyAsString;
        $sut = $this->getStorageStub();
        $sut->save($givenKey, $givenItem);
        $result = $sut->findKey($givenItem);
        $this->assertSame($keyAsString, (string) $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a given item does not exist in storage")]
    public function test13()
    {
        $givenItem = new FakeModel();
        $sut = $this->getStorageStub();
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when an object that does not implement the equals method does not exist in storage")]
    public function test14()
    {
        $givenItem = (object) ["name" => "eric"];
        $sut = $this->getStorageStub();
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a scalar value does not exist in storage")]
    public function test15()
    {
        $givenItem = 2 ** 44;
        $sut = $this->getStorageStub();
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a given item does not exist in storage")]
    public function test13b()
    {
        $anotherKeyOfStorable = uniqid();
        $givenItem = new FakeModel();
        $anotherItem = new FakeModel("another", "item");
        $sut = $this->getStorageStub();
        $sut->save($anotherKeyOfStorable, $anotherItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when an object that does not implement the equals method does not exist in storage")]
    public function test14b()
    {
        $anotherKeyOfStorable = uniqid();
        $givenItem = (object) ["name" => "eric"];
        $anotherItem = (object) ["name" => "someone else"];
        $sut = $this->getStorageStub();
        $sut->save($anotherKeyOfStorable, $anotherItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return instance of KeyNotFound when a scalar value does not exist in storage")]
    public function test15b()
    {
        $anotherKeyOfStorable = uniqid();
        $anotherItem = 2 ** 43;
        $givenItem = 2 ** 44;
        $sut = $this->getStorageStub();
        $sut->save($anotherKeyOfStorable, $anotherItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when there are no values in storage")]
    public function testa()
    {
        $sut = $this->getStorageStub();
        $givenItem = 2 ** 44;
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item is empty")]
    public function testb()
    {
        $key = uniqid();
        $sut = $this->getStorageStub();
        $storedItem = (object) [];
        $givenItem = (object) ["other" => "values"];
        $sut->save($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item and given item do not have the same number of properties")]
    public function testc()
    {
        $key = uniqid();
        $sut = $this->getStorageStub();
        $storedItem = (object) ["a" => "prop"];
        $givenItem = (object) ["a" => "prop", "another" => "prop"];
        $sut->save($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item and given item have the same values but different property names")]
    public function testd()
    {
        $key = uniqid();
        $sut = $this->getStorageStub();
        $storedItem = (object) ["a" => "prop"];
        $givenItem = (object) ["b" => "prop"];
        $sut->save($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the given item is not an object but the stored item is an object")]
    public function teste()
    {
        $key = uniqid();
        $sut = $this->getStorageStub();
        $storedItem = (object) ["a" => "prop"];
        $givenItem = ["a" => "prop"];
        $sut->save($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall return an instance of KeyNotFound when the stored item is not an object but the given item is an object")]
    public function testf()
    {
        $key = uniqid();
        $sut = $this->getStorageStub();
        $storedItem = ["a" => "prop"];
        $givenItem = (object) ["a" => "prop"];
        $sut->save($key, $storedItem);
        $result = $sut->findKey($givenItem);
        $this->assertInstanceOf(KeyNotFound::class, $result);
    }

    #[TestDox("Shall call onInit of the given life cycle hooks when created")]
    public function testg()
    {
        $spy = $this->createMock(LifeCycleHooks::class);

        $spy->expects($this->once())->method("onInit");

        new class($spy) extends AbstractStorage {};
    }

    #[TestDox("Shall call onDestroy of the given life cycle hooks when destroyed")]
    public function testh()
    {
        $spy = $this->createMock(LifeCycleHooks::class);

        $spy->expects($this->once())->method("onDestroy");

        $a = new class($spy) extends AbstractStorage {};

        /**
         * call destructor
         */
        $a = null;
    }
}
