<p align="center">
    <img width="240" src="./phpolar.svg" />
</p>

# PHPolar Data Storage Abstraction

[![Coverage Status](https://coveralls.io/repos/github/phpolar/storage/badge.svg)](https://coveralls.io/github/phpolar/storage) [![Latest Stable Version](http://poser.pugx.org/phpolar/storage/v)](https://packagist.org/packages/phpolar/storage) [![Total Downloads](http://poser.pugx.org/phpolar/storage/downloads)](https://packagist.org/packages/phpolar/storage) [![Latest Unstable Version](http://poser.pugx.org/phpolar/storage/v/unstable)](https://packagist.org/packages/phpolar/storage) [![License](http://poser.pugx.org/phpolar/storage/license)](https://packagist.org/packages/phpolar/storage) [![PHP Version Require](http://poser.pugx.org/phpolar/storage/require/php)](https://packagist.org/packages/phpolar/storage)

## Use to create persistence layer repositories, services, etc. with improved type safety

## Usage

```php
class KafkaStorage extends AbstractStorage
{
    public function __construct(
        // ...
    ) {
        parent::__construct($lifeCycleHooks);
        // ...
    }
    // ...
}


$key0 = uniqid();

$kafkaStorage->save($key0, $data);

$result = $kafkaStorage->find($key0);

$item0 = $result
    ->orElse(static function () {
            $this->logger->warn($notFoundMessage);
            return new ResourceNotFound();
        }
    )
    ->tryUnwrap()

$numItems = $kafkaStorage->count();

$allItems = $kafkaStorage->findAll();

$kafka->remove($key0);

$kafka->clear();

```
