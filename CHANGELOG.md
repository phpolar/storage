## 5.1.0 (2025-05-26)

### Fix

- remove redundant namespace sub-level (#80)

## 5.0.0 (2025-05-26)

### BREAKING CHANGE

- AbstractStorage all methods. Item, ItemKey, ItemFound and ItemNotFound have been removed. Result object and StorageContext have been added.

### Feat

- overhaul api (#79)
- **AbstractStorage**: add life-cycle hooks (#78)

## 4.0.0 (2025-05-07)

### Feat

- shorten method names

## 3.1.1 (2025-04-16)

### Fix

- add workflow permissions

## 3.1.0 (2025-03-20)

### Fix

- use php 8.3 or greater

## 3.0.0 (2025-01-18)

### BREAKING CHANGE

- AbstractStorage::getByKey now returns ItemFound instead of Item.

### Feat

- **AbstractStorage**: return item found type

## 2.0.0 (2023-07-03)

### BREAKING CHANGE

- `composer require phpolar/storage` instead of `composer require phpolar/phpolar-storage`

### Refactor

- rename project

## 1.2.2 (2023-05-05)

### Fix

- **AbstractStorage**: update key map when replacing items

## 1.2.1 (2023-04-30)

### Fix

- upgrade guzzle to 2.4.5

## 1.2.0 (2023-03-05)

### Feat

- **AbstractStorage**: load when instance is created

## 1.1.0 (2023-03-05)

### Feat

- **AbstractStorage**: change visibility of `load` to protected
- **check-for-object-and-update-config**: The ci builds were failing

### Fix

- **AbstractStorage**: improve key finding

## 1.0.1 (2023-02-12)

### Fix

- exclude unnecessary files from dist

## 1.0.0 (2023-02-09)

### Feat

- update test coverage badge

## 0.6.1 (2023-02-09)

### Refactor

- **AbstractStorage**: use reflection properties instead of object iteration

## 0.6.0 (2023-02-09)

### Feat

- add support for finding a key of a given item

## 0.5.0 (2023-02-09)

### Feat

- **AbstractStorage**: add the load method

## 0.4.0 (2023-02-09)

### Feat

- add support for different keys with the same value

## 0.3.0 (2023-02-09)

### Feat

- **AbstractStorage**: add commit method

## 0.2.0 (2023-02-07)

### Feat

- initial commit

## 0.1.0 (2023-01-31)

### Feat

- initial commit
