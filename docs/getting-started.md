# Getting Started

## Installation

This package is installable and autoloadable via [Composer](https://getcomposer.org/)
as [atlas/query](https://packagist.org/packages/atlas/query). Add the following lines
to your `composer.json` file, then call `composer update`.

```json
{
    "require": {
        "atlas/query": "~1.0"
    }
}
```

## Instantiation

Given an existing PDO instance, you can create a query using its static `new()`
method:

```php
<?php
use Atlas\Query\Select;
use Atlas\Query\Insert;
use Atlas\Query\Update;
use Atlas\Query\Delete;

$pdo = new PDO('sqlite::memory:');

$select = Select::new($pdo);
$insert = Insert::new($pdo);
$udpate = Update::new($pdo);
$delete = Delete::new($pdo);
```

(This also works with an existing [Atlas.Pdo][] _Connection_ instance.)

Alternatively, instantiate a _QueryFactory_ ...

```php
$queryFactory = new \Atlas\Query\QueryFactory();
```

...and then use the factory to create query objects for an [Atlas.Pdo][]
_Connection_.

```php
$connection = \Atlas\Pdo\Connection::new('sqlite::memory:');

$select = $queryFactory->newSelect($connection);
$insert = $queryFactory->newInsert($connection);
$update = $queryFactory->newUpdate($connection);
$delete = $queryFactory->newDelete($connection);
```

[Atlas.Pdo]: https://github.com/atlasphp/Atlas.Pdo
