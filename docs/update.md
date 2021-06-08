# UPDATE

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$pdoStatement = $update->perform();
```

If you added a `RETURNING` clause with the `returning()` method, you can
retrieve those column values with the returned _PDOStatement_:

```php
$pdoStatement = $update->perform();
$values = $pdoStatement->fetch(); // : array
```
