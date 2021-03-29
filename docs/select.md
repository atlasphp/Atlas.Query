
# SELECT

## Building The Query

### WITH

To add one or more Common Table Expressions (CTEs), use the `with()` method:

```php
// WITH cte_1 (foo, bar, baz) AS (SELECT ...)
$select->with('cte_2', ['foo', 'bar', 'baz'], "SELECT ...");

// WITH cte_2 AS (SELECT ...)
$select->with('cte_2', [], "SELECT ...")
```

To enable or disable recursive CTEs, call `withRecursive()` method:

```php
// enable
$select
    ->withRecursive()
    ->with(...);

// disable
$select->withRecursive(false);
```

You can use any kind of query as a CTE; further, you can pass a query object
instead of a query string as the final `with()` argument:

```php
$cteQuery = Select::new($connection);
$cteQuery->...;

$select->with('cte_2', [], $cteQuery);
```

### Columns

To add columns to the _Select_, use the `columns()` method and pass each column as
a variadic argument.

```php
// SELECT id, name AS namecol, COUNT(foo) AS foo_count
$select
    ->columns('id')
    ->columns('name AS namecol', 'COUNT(foo) AS foo_count');
```

### FROM

To add a `FROM` clause, use the `from()` method:

```php
// FROM foo, bar AS b
$select
    ->from('foo')
    ->from('bar AS b');
```

### JOIN

(All `JOIN` methods support [inline value binding](binding.md) via optional trailing arguments.)

To add a `JOIN` clause, use the `join()` method:

```php
// LEFT JOIN doom AS d ON foo.id = d.foo_id
$select->join(
    'LEFT',
    'doom AS d',
    'foo.id = d.foo_id'
);
```

You can concatenate onto the end of the most-recent `JOIN` using the `catJoin()`
method:

```php
// LEFT JOIN doom AS d ON foo.id = d.foo_if AND d.bar = :_1_1_ AND d.baz = :_1_2_
$select
    ->join(
        'LEFT',
        'doom AS d',
        'foo.id = d.foo_id AND d.bar = ',
        $bar_value
    )->catJoin(' AND d.baz = ', $baz_value);
```

### WHERE

(All `WHERE` methods support [implicit and sprintf() inline value binding](binding.md).)

To add `WHERE` conditions, use the `where()` method. Additional calls to
`where()` will implicitly AND the subsequent condition.

```php
// WHERE bar > :_1_1_ AND zim >= :_1_2_ AND baz :_1_3_
$select
    ->where('bar > ', $bar_value)
    ->where('zim >= ', $zim_value)
    ->andWhere('baz < ', $baz_value);
```

Use `orWhere()` to OR the subsequent condition.

```php
// WHERE bar > :_1_1_ OR zim >= :_1_2_
$select
    ->where('bar > ', $bar_value)
    ->orWhere('zim >= ', $zim_value)
```

You can concatenate onto the end of the most-recent `WHERE` condition using the
`catWhere()` method:

```php
// WHERE bar > :_1_1_ OR (foo = 88 AND bar < :_1_2_)
$select
    ->where('bar > ', $bar_value)
    ->orWhere('(')
    ->catWhere('foo = 88')
    ->catWhere(' AND bar < ', $bar_value)
    ->catWhere(')');
```

Each of the WHERE-related methods has an `sprintf` variation as well:

```php
// WHERE bar BETWEEN :_1_1_ AND :_1_2_
// AND baz BETWEEN :_1_3_ AND :_1_4_
// OR dib BETWEEN :_1_5_ AND :_1_6_
// ...
$select
    ->whereSprintf('bar BETWEEN %s AND %s', $bar_low, $bar_high)
    ->andWhereSprintf('baz BETWEEN %s AND %s', $baz_low, $baz_high)
    ->orWhereSprintf('dib BETWEEN %s AND %s', $dib_low, $dib_high)
    ->catWhereSprintf(...);
```

#### Convenience Equality

There is an additional `whereEquals()` convenience method that adds a series of
`AND`ed equality conditions for you based on an array of key-value pairs.

Given an array value, the condition will be `IN ()`. Given a null value, the
condition will be `IS NULL`. For all other values, the condition will be `=`. If
you pass a value without a key, that value will be used as a raw unescaped
condition.

For example:

```php
// WHERE foo IN (:_1_1_, :_1_2_, :_1_3_) AND bar IS NULL AND baz = :_1_4_ AND zim = NOW()
$select->whereEquals([
    'foo' => ['a', 'b', 'c'],
    'bar' => null,
    'baz' => 'dib',
    'zim = NOW()'
]);
```

### GROUP BY

To add `GROUP BY` expressions, use the `groupBy()` method and pass each
expression as a variadic argument.

```php
// GROUP BY foo, bar, baz
$select
    ->groupBy('foo')
    ->groupBy('bar', 'baz');
```

### HAVING

(All `HAVING` methods support [implicit and sprintf() inline value binding](binding.md).)

The `HAVING` methods work just like their equivalent WHERE methods:

- `having()` and `andHaving()` AND a HAVING condition
- `orHaving()` ORs a HAVING condition
- `catHaving()` concatenates onto the end of the most-recent HAVING condition
- `havingSprintf()` and `andHavingSprintf()` AND a HAVING condition with sprintf()
- `orHavingSprintf()` ORs a HAVING condition with sprintf()
- `catHavingSprintf()` concatenates onto the end of the most-recent HAVING condition with sprintf()

### ORDER BY

To add `ORDER BY` expressions, use the `orderBy()` method and pass each
expression as a variadic argument.

```php
// ORDER BY foo, bar, baz
$select
    ->orderBy('foo')
    ->orderBy('bar', 'baz');
```

By default, results are ordered in ascending order (ASC). To sort in a different
order, add the revelant keyword. For example, to sort in descending order:

```php
// ORDER BY foo DESC
$select
    ->orderBy('foo DESC')
```

### LIMIT, OFFSET, and Paging

To set a `LIMIT` and `OFFSET`, use the `limit()` and `offset()` methods.

```php
// LIMIT 10 OFFSET 40
$select
    ->limit(10)
    ->offset(40);
```

Alternatively, you can limit by "pages" using the `page()` and `perPage()`
methods:

```php
// LIMIT 10 OFFSET 40
$select
    ->page(5)
    ->perPage(10);
```

### DISTINCT, FOR UPDATE, and Other Flags

You can set `DISTINCT` and `FOR UPDATE` flags on the query like so:

```php
$select
    ->distinct()
    ->forUpdate();
```

Each of those methods take an option boolean parameter to enable (`true`) or
disable (`false`) the flag.

You can set flags recognized by your database server using the `setFlag()`
method. For example, you can set a MySQL `HIGH_PRIORITY` flag like so:

```php
// SELECT HIGH_PRIORITY * FROM foo
$select
    ->columns('*')
    ->from('foo')
    ->setFlag('HIGH_PRIORITY');
```

### UNION

To `UNION` or `UNION ALL` the current _Select_ with a followup query, call one
the `union*()` methods:

```php
// SELECT id, name FROM foo
// UNION
// SELECT id, name FROM bar
$select
    ->columns('id', 'name')
    ->from('foo')
    ->union()
    ->columns('id', 'name')
    ->from('bar');

// SELECT id, name FROM foo
// UNION ALL
// SELECT id, name FROM bar
$select
    ->columns('id', 'name')
    ->from('foo')
    ->unionAll()
    ->columns('id', 'name')
    ->from('bar');
```

## Resetting Query Elements

The _Select_ class comes with the following methods to "reset" various clauses
a blank state. This can be useful when reusing the same query in different
variations (e.g., to re-issue a query to get a `COUNT(*)` without a `LIMIT`, to
find the total number of rows to be paginated over).

- `reset()` removes all clauses from the query.
- `resetColumns()` removes all the columns to be selected.
- `resetFrom()` removes the FROM clause, including all JOIN sub-clauses.
- `resetWhere()` removes all WHERE conditions.
- `resetGroupBy()` removes all GROUP BY expressions.
- `resetHaving()` removes all HAVING conditions.
- `resetOrderBy()` removes all ORDER BY expressions.
- `resetLimit()` removes all LIMIT, OFFSET, and paging values.
- `resetFlags()` removes all flags.

Resetting only works on the current SELECT being built; it has no effect on
queries that are already part of UNION.

## Subselect Objects

If you want create a subselect, call the `subSelect()` method:

```php
$subSelect = $select->subSelect();
```

When you are done building the subselect, give it an alias using the `as()`
method; the object itself can be used in the desired condition or expression.

The following is a contrived example:

```php
// SELECT * FROM (
//     SELECT id, name
//     FROM foo
//     WHERE id > :_1_1_
// ) AS sub_alias
// WHERE LENGTH(sub_alias.name) > :_1_2_
$select
    ->columns('*')
    ->from(
        $select->subSelect()
            ->columns('id', 'name')
            ->from('foo')
            ->where('id > ', $id)
            ->as('sub_alias')
        )
    )
    ->where('LENGTH(sub_alias.name) > ', $length);
```

Other examples include:

```php
// joining on a subselect
$select->join(
    'LEFT',
    $select->subSelect()->...->as('sub_alias'),
    'foo.id = sub_alias.id',
);

// binding a subselect inline
$select->where(
    'foo IN ',
    $select->subSelect()->...
);
```

## Performing The Query

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

- `fetchAll() : array`
- `fetchAffected() : int`
- `fetchColumn(int $column = 0) : array`
- `fetchGroup(int $style = PDO::FETCH_COLUMN) : array`
- `fetchKeyPair() : array`
- `fetchObject(string $class = 'stdClass', array $args = []) : object`
- `fetchObjects(string $class = 'stdClass', array $args = []) : array`
- `fetchOne() : ?array`
- `fetchUnique() : array`
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
[Atlas.Pdo Connection](http://atlasphp.io/cassini/pdo/connection.html)
documentation.
