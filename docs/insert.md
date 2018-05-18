# INSERT

## Building The Query

### Into

Use the `into()` method to specify the table to insert into.

```php
$insert->into('foo');
```

### Columns

You can set a named placeholder and its corresponding bound value using the
`column()` method.

```php
// INSERT INTO foo (bar) VALUES (:bar)
$insert->column('bar', $bar_value);
```

Note that the PDO parameter type will automatically be set for strings,
integers, floats, and nulls. If you want to set a PDO parameter type yourself,
pass it as an optional third parameter.

```php
// INSERT INTO foo (bar) VALUES (:bar);
$insert->column('bar', $bar_value, \PDO::PARAM_LOB);
```

You can set several placeholders and their corresponding values all at once by
using the `columns()` method:

```php
// INSERT INTO foo (bar) VALUES (:bar)
$insert->columns([
    'bar' => $bar_value,
    'baz' => $baz_value
]);
```

However, you will not be able to specify a particular PDO parameter type when
doing do.

Bound values are automatically quoted and escaped; in some cases, this will be
inappropriate, so you can use the `raw()` method to set column to an unquoted
and unescaped expression.

```php
// INSERT INTO foo (bar) VALUES (NOW())
$insert->raw('bar', 'NOW()');
```

### RETURNING

Some databases (notably PostgreSQL) recognize a `RETURNING` clause. You can add
one to the _Insert_ using the `returning()` method, specifying columns as
variadic arguments.

```php
// INSERT ... RETURNING foo, bar, baz
$insert
    ->returning('foo')
    ->returning('bar', 'baz');
```

### Flags

You can set flags recognized by your database server using the `setFlag()`
method. For example, you can set a MySQL `LOW_PRIORITY` flag like so:

```php
// INSERT LOW_PRIORITY INTO foo (bar) VALUES (:bar)
$insert
    ->into('foo')
    ->column('bar', $bar_value)
    ->setFlag('LOW_PRIORITY');
```

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $insert->perform(); // : PDOStatement
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
