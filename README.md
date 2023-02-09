<p align="center">
    <img width="240" src="./phpolar.svg" />
</p>

# PHPolar Data Storage Abstraction

[![Coverage Status](https://coveralls.io/repos/github/phpolar/phpolar-storage/badge.svg?branch=main)](https://coveralls.io/github/phpolar/phpolar-storage?branch=main) [![Latest Stable Version](http://poser.pugx.org/phpolar/phpolar-storage/v)](https://packagist.org/packages/phpolar/phpolar-storage) [![Total Downloads](http://poser.pugx.org/phpolar/phpolar-storage/downloads)](https://packagist.org/packages/phpolar/phpolar-storage) [![Latest Unstable Version](http://poser.pugx.org/phpolar/phpolar-storage/v/unstable)](https://packagist.org/packages/phpolar/phpolar-storage) [![License](http://poser.pugx.org/phpolar/phpolar-storage/license)](https://packagist.org/packages/phpolar/phpolar-storage) [![PHP Version Require](http://poser.pugx.org/phpolar/phpolar-storage/require/php)](https://packagist.org/packages/phpolar/phpolar-storage)
## Use to create persistence layer repositories, services, etc. with improved type safety.

## Usage


```php
class KafkaStorage extends AbstractStorage
{
    public function __construct(
        // ...
    ) {
        parent::__construct();
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
