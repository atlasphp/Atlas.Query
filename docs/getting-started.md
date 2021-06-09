# Getting Started

## Installation

This package is installable and autoloadable via [Composer](https://getcomposer.org/)
as [atlas/query](https://packagist.org/packages/atlas/query).

```sh
$ composer require atlas/query ^2.0
```

## Instantiation

Given an existing _Connection_ instance from [Atlas.Pdo][], you can create a
query object using the static `new()` method of the query type:

```php
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

Alternatively, you can pass an existing PDO instance, which will get wrapped
in a _Connection_ for you:

```php
use PDO;

$pdo = new PDO('sqlite::memory:');
$select = Select::new($pdo);
```

You can also pass PDO construction arguments, in which case a new _Connection_
will be created for you:

```php
// PDO construction arguments
$insert = Insert::new('sqlite::memory');
```

Once you have a _Query_ object, you will then be able to *both* build the query
statement *and* and perform it through that _Connection_.

[Atlas.Pdo]: https://github.com/atlasphp/Atlas.Pdo
