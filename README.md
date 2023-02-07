<p align="center">
    <img width="240" src="./phpolar.svg" />
</p>

# PHPolar Data Storage Abstraction

## Use to create persistence layer repositories, services, etc. with improved type safety.

## Usage


```php
class KafkaStorage extends AbstractStorage
{
    public function __construct(
        WeakMap $map,
        // ...
    ) {
        parent::__construct($map);
        // ...
    }
    // ...
}


$key0 = new ItemKey(uniqid());

$kafkaStorage->storeByKey($key0, $data);

$item0 = $kafkaStorage->getByKey($key0);

$numItems = $kafkaStorage->count();

$allItems = $kafkaStorage->getAll();

$kafka->removeByKey($key0);

$kafka->clear();

```