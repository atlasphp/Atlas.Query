# Instantiation

First, instantiate a _QueryFactory_:

```php
$queryFactory = new \Atlas\Query\QueryFactory();
```

You can then use the factory to create query objects for an [Atlas.Pdo][]
_Connection_.

```php
$connection = \Atlas\Pdo\Connection::new('sqlite::memory:');

$select = $queryFactory->newSelect($connection);
$insert = $queryFactory->newInsert($connection);
$update = $queryFactory->newUpdate($connection);
$delete = $queryFactory->newDelete($connection);
```

[Atlas.Pdo]: https://github.com/atlasphp/Atlas.Pdo
