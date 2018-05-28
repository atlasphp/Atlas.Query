# UPDATE

## Building The Query

### Table

Use the `table()` method to specify the table to update.

```php
$update->table('foo');
```

### Columns

You can set a named placeholder and its corresponding bound value using the
`column()` method.

```php
// UPDATE foo SET bar = :bar
$update->column('bar', $bar_value);
```

Note that the PDO parameter type will automatically be set for strings,
integers, floats, and nulls. If you want to set a PDO parameter type yourself,
pass it as an optional third parameter.

```php
// UPDATE foo SET bar = :bar
$update->column('bar', $bar_value, \PDO::PARAM_LOB);
```

You can set several placeholders and their corresponding values all at once by
using the `columns()` method:

```php
// UPDATE foo SET bar = :bar, baz = :baz
$update->columns([
    'bar' => $bar_value,
    'baz' => $baz_value
]);
```

However, you will not be able to specify a particular PDO parameter type when
doing do.

Bound values are automatically quoted and escaped; in some cases, this will be
inappropriate, so you can use the `raw()` method to set column to an unquoted
and unescaped expression.

```pho
// UPDATE foo SET bar = NOW()
$update->raw('bar', 'NOW()');
```
### WHERE

(All `WHERE` methods support [inline value binding](binding.md) via optional trailing arguments.)

The _Update_ `WHERE` methods work just like their equivalent _Select_ methods:

- `where()` and `andWhere()` AND a WHERE condition
- `orWhere()` ORs a WHERE condition
- `catWhere()` concatenates onto the end of the most-recent WHERE condition.

### ORDER BY

Some databases (notably MySQL) recognize an `ORDER BY` clause. You can add one
to the _Update_ with the `orderBy()` method; pass each expression as a variadic
argument.

```php
// UPDATE ... ORDER BY foo, bar, baz
$update
    ->orderBy('foo')
    ->orderBy('bar', 'baz');
```

### LIMIT and OFFSET

Some databases (notably MySQL and SQLite) recognize a `LIMIT` clause; others
(notably SQLite) recognize an additional `OFFSET`. You can add these to the
_Update_ with the `limit()` and `offset()` methods:

```php
// LIMIT 10 OFFSET 40
$update
    ->limit(10)
    ->offset(40);
```

### RETURNING

Some databases (notably PostgreSQL) recognize a `RETURNING` clause. You can add
one to the _Update_ using the `returning()` method, specifying columns as
variadic arguments.

```php
// UPDATE ... RETURNING foo, bar, baz
$update
    ->returning('foo')
    ->returning('bar', 'baz');
```

### Flags

You can set flags recognized by your database server using the `setFlag()`
method. For example, you can set a MySQL `LOW_PRIORITY` flag like so:

```php
// UPDATE LOW_PRIORITY foo SET bar = :bar WHERE baz = :__1__
$update
    ->table('foo')
    ->column('bar', $bar_value)
    ->where('baz = ', $baz_value)
    ->setFlag('LOW_PRIORITY');
```

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $select->perform(); // : PDOStatement
```
