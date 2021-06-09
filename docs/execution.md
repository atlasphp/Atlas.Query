# Query Execution

Because each _Query_ object extends its relevant
[Atlas.Statement](http://atlasphp.io/dymaxion/statement) class,
the _Statement_ methods are available to build the _Query_ statement.

Thus, by using a _Query_, you can *both* build *and* execute the statement with
a single object.

## SELECT

After you [build a SELECT statement](http://atlasphp.io/dymaxion/statement/select.html),
call the `perform()` method to execute it and get back a _PDOStatement_.

```php
$pdoStatement = $select->perform();
```

### Fetching and Yielding

The _Select_ proxies all `fetch*()` and `yield()` method calls to the underlying
_Connection_ object via the magic `__call()` method:

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
[Atlas.Pdo _Connection_](http://atlasphp.io/dymaxion/pdo/connection.html)
documentation.

## INSERT

After you [build an INSERT statement](http://atlasphp.io/dymaxion/statement/insert.html),
call the `perform()` method to execute it and get back a _PDOStatement_.

```php
$pdoStatement = $insert->perform();
```

### Last Insert ID

If the database autoincrements a column while performing the query, you can get
back that value using the `getLastInsertId()` method:

```php
$id = $insert->getLastInsertId();
```

> **Note:**
>
> You can pass a sequence name as an optional parameter to `getLastInsertId()`;
> this may be required with PostgreSQL.

### RETURNING

If you added a `RETURNING` clause with the `returning()` method, you can
retrieve those column values with the returned _PDOStatement_:

```php
$pdoStatement = $insert->perform();
$values = $pdoStatement->fetch(); // : array
```

## UPDATE

After you [build an UPDATE statement](http://atlasphp.io/dymaxion/statement/update.html),
call the `perform()` method to execute it and get back a _PDOStatement_.

```php
$pdoStatement = $update->perform();
```

If you added a `RETURNING` clause with the `returning()` method, you can
retrieve those column values with the returned _PDOStatement_:

```php
$pdoStatement = $update->perform();
$values = $pdoStatement->fetch(); // : array
```

### RETURNING

If you added a `RETURNING` clause with the `returning()` method, you can
retrieve those column values with the returned _PDOStatement_:

```php
$pdoStatement = $update->perform();
$values = $pdoStatement->fetch(); // : array
```

## DELETE

After you [build a DELETE statement](http://atlasphp.io/dymaxion/statement/delete.html),
call the `perform()` method to execute it and get back a _PDOStatement_.

```php
$pdoStatement = $delete->perform(); // : PDOStatement
```
