# DELETE

## Building The Query

### FROM

Use the `from()` method to specify FROM expression.

```php
$delete->from('foo');
```

### WHERE

(All `WHERE` methods support [inline value binding](binding.md) via optional trailing arguments.)

The _Delete_ `WHERE` methods work just like their equivalent _Select_ methods:

- `where()` and `andWhere()` AND a WHERE condition
- `orWhere()` ORs a WHERE condition
- `catWhere()` concatenates onto the end of the most-recent WHERE condition.

### ORDER BY

Some databases (notably MySQL) recognize an `ORDER BY` clause. You can add one
to the _Delete_ with the `orderBy()` method; pass each expression as a variadic
argument.

```php
// DELETE ... ORDER BY foo, bar, baz
$delete
    ->orderBy('foo')
    ->orderBy('bar', 'baz');
```

### LIMIT and OFFSET

Some databases (notably MySQL and SQLite) recognize a `LIMIT` clause; others
(notably SQLite) recognize an additional `OFFSET`. You can add these to the
_Delete_ with the `limit()` and `offset()` methods:

```php
// LIMIT 10 OFFSET 40
$delete
    ->limit(10)
    ->offset(40);
```

### RETURNING

Some databases (notably PostgreSQL) recognize a `RETURNING` clause. You can add
one to the _Delete_ using the `returning()` method, specifying columns as
variadic arguments.

```php
// DELETE ... RETURNING foo, bar, baz
$delete
    ->returning('foo')
    ->returning('bar', 'baz');
```

### Flags

You can set flags recognized by your database server using the `setFlag()`
method. For example, you can set a MySQL `LOW_PRIORITY` flag like so:

```php
// DELETE LOW_PRIORITY foo WHERE baz = :__1__
$delete
    ->from('foo')
    ->where('baz = ', $baz_value)
    ->setFlag('LOW_PRIORITY');
```

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $select->perform(); // : PDOStatement
```
