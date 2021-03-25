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

Given an existing Connection instance from [Atlas.Pdo][], you can create a query
using the static `new()` method of the query type:

```php
<?php
use Atlas\Pdo\Connection;
use Atlas\Query\Select;
use Atlas\Query\Insert;
use Atlas\Query\Update;
use Atlas\Query\Delete;

$connection = Connection::new('sqlite::memory:');

$select = Select::new($connection);
$insert = Insert::new($connection);
$udpate = Update::new($connection);
$delete = Delete::new($connection);
```

Alternatively, you can pass an existing PDO instance, or PDO construction arguments:

```php
use PDO;

// existing PDO instance
$pdo = new PDO('sqlite::memory');
$select = Select::new($pdo);

// PDO construction arguments
$insert = Insert::new('sqlite::memory');
```

[Atlas.Pdo]: https://github.com/atlasphp/Atlas.Pdo
