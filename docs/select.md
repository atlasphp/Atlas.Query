# SELECT

## Building A Query

Build a _Select_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

### Columns

To add columns to the SELECT, use the `columns()` method and pass each
column as a variadic argument.

```php
$select->columns(
    'id',                       // column name
    'name AS namecol',          // aliased column name
    'COUNT(foo) AS foo_count'   // embed calculations directly
);
```

### FROM

To add a FROM clause, call the `from()` method as needed:

```php
// FROM foo, "bar" as "b"
$select
    ->from('foo')           // table name
    ->from('bar AS b');     // aliased table name
```


### JOIN

To add a JOIN clause, call the `join()` method as needed:

```php
// LEFT JOIN doom AS d ON foo.id = d.foo_id
$select->join(
    'LEFT',             // the join-type
    'doom AS d',        // join to this table ...
    'foo.id = d.foo_id' // ... ON these conditions
);
```

Also `catJoin()`.


### WHERE

To add WHERE conditions, call the `where()` method as needed. Subsequent calls to
`where()` will AND the condition, unless you call `orWhere()`, in which case it
will OR the condition.

```php
    ->where('bar > :bar')           // WHERE bar > :bar
    ->andWhere('zim = :zim')           // AND zim = :ZIM
    ->orWhere('baz < :baz')         // OR baz < :baz
    ->catWhere('...')
```


### GROUP BY

```php
    ->groupBy('dib')              // GROUP BY these columns
```

### HAVING

```php
    ->having('foo = :foo')          // AND HAVING these conditions
    ->andHaving('bar > ', 'bar_val')  // bind 'bar_val' to a number-named placeholder
    ->orHaving('baz < :baz')        // OR HAVING these conditions
```


### ORDER BY

```php
    ->orderBy('baz')              // ORDER BY these columns
```

### LIMIT, OFFSET, and Paging

```php
    ->limit(10)                     // LIMIT 10
    ->offset(40)                    // OFFSET 40
    ->page($page)
    ->getPage()
    ->setPerPage($perPage)
    ->getPerPage()
```

### UNION

```php
    ->union()                       // UNION with a followup SELECT
    ->unionAll()                    // UNION ALL with a followup SELECT
```

### Flags

```php
    ->forUpdate()                   // FOR UPDATE
    ->distinct()                    // SELECT DISTINCT
```

### Binding Values

```php
    ->bindValue('foo', 'foo_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values to named placeholders
        'bar' => 'bar_val',
        'baz' => 'baz_val',
    ]);
```

All of the `*join*()` methods take optional trailing variadic argument
spcifying a value to bind inline at the end of the JOIN clause, optionally
followed by a PDO::PARAM type.


### Resetting Query Elements

The _Select_ class comes with the following methods to "reset" various clauses
a blank state. This can be useful when reusing the same query in different
variations (e.g., to re-issue a query to get a `COUNT(*)` without a `LIMIT`, to
find the total number of rows to be paginated over).

- `resetCols()` removes all columns
- `resetTables()` removes all `FROM` and `JOIN` clauses
- `resetWhere()`, `resetGroupBy()`, `resetHaving()`, and `resetOrderBy()`
  remove the respective clauses
- `resetUnions()` removes all `UNION` and `UNION ALL` clauses
- `resetFlags()` removes all database-engine-specific flags
- `resetBindValues()` removes all values bound to named placeholders

    public function reset()
    public function resetWhere()
    public function resetGroupBy()
    public function resetHaving()
    public function resetOrderBy()

### Sub-Selects

If you want to SELECT FROM a subselect, do so by calling `fromSubSelect()`.
Pass both the subselect query string, and an alias for the subselect:

```php
// FROM (SELECT ...) AS "my_sub"
$select->fromSubSelect('SELECT ...', 'my_sub');
```

You can also pass a SELECT object as the subselect, instead of a query string.
This allows you to create an entire SELECT query and use it as a subselect.

As with FROM, you can join to a subselect using `joinSubSelect()`:

```php
// INNER JOIN (SELECT ...) AS subjoin ON subjoin.id = foo.id
$select->joinSubSelect(
    'INNER',                    // left/inner/natural/etc
    'SELECT ...',               // the subselect to join on
    'subjoin',                  // AS this name
    'subjoin.id = foo.id'       // ON these conditions
);
```

Also as with FROM, you can pass a SELECT object instead of a query string as the
subselect.

## Performing The Query

To execute the _Select_ and get back a result, call the `perform()` method, or
any of the `fetch*()` or `yield*()` methods. (These methods are proxied through
the query object to the underlying Atlas.Pdo _Connection_.)

```php
$result = $select->perform();  // : PDOStatement
$result = $select->fetchAll(); // : array
$result = $select->yieldAll(); // : Generator
```
