# Common Table Expressions

Every Atlas.Query object supports Common Table Expressions. To add one or more
CTE to a query, call the `with*()` methods:

```php
// WITH cte_1 AS (SELECT ...)
$insert->with('cte_1', "SELECT ...")

// WITH cte_2 (foo, bar, baz) AS (SELECT ...)
$update->withColumns('cte_2', ['foo', 'bar', 'baz'], "SELECT ...");
```

> **Note:**
>
> You can use any kind of query as a CTE, not just a SELECT.

To enable or disable recursive CTEs, call `withRecursive()`:

```php
// enable
$select->withRecursive();

// disable
$select->withRecursive(false);
```

Further, you can pass a query object instead of a query string:

```php
$cteQuery = Select::new($connection);
$cteQuery->...;

$delete->with('cte_3', $cteQuery);
```

> **Note:**
>
> Any values bound to the CTE query will be transferred to the main query.
