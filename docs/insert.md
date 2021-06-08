# INSERT

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

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
