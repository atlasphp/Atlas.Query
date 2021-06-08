# SELECT

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $select->perform();
```

The _Select_ proxies all `fetch*()` and `yield()` method calls to the underlying
_Connection_ object via the magic `__call()` method, which means you can both
build the query and perform it using the same _Select_ object.

The _Connection_ `fetch*()` and `yield*()` methods proxied through the _Select_
are as follows:

- `fetchAll() : array|false`
- `fetchAffected() : int`
- `fetchColumn(int $column = 0) : array|false`
- `fetchGroup(int $style = PDO::FETCH_COLUMN) : array|false`
- `fetchKeyPair() : array|false`
- `fetchObject(string $class = 'stdClass', array $args = []) : object|false`
- `fetchObjects(string $class = 'stdClass', array $args = []) : array|false`
- `fetchOne() : array|false`
- `fetchUnique() : array|false`
- `fetchValue() : mixed`
- `yieldAll() : Generator`
- `yieldColumn(int $column = 0) : Generator`
- `yieldKeyPair() : Generator`
- `yieldObjects(string $class = 'stdClass', array $args = []) : Generator`
- `yieldUnique() : Generator`

For example, to build a query and get back an array of all results:

```php
// SELECT * FROM foo WHERE bar > :_1_1_
$result = $select
    ->columns('*')
    ->from('foo')
    ->where('bar > ', $value)
    ->fetchAll();

foreach ($result as $key => $val) {
    echo $val['bar'] . PHP_EOL;
}
```

For more information on the `fetch*()` and `yield*()` methods, please see the
[Atlas.Pdo Connection](http://atlasphp.io/dymaxion/pdo/connection.html)
documentation.
