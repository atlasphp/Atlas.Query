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
// INSERT INTO foo (bar) VALUES (:bar);
$insert->column('bar', $bar_value);
```

Note that the PDO parameter type will automatically be set for strings,
integers, floats, and nulls. If you want to set an PDO parameter type yourself,
pass it as an optional third parameter.

```php
// INSERT INTO foo (bar) VALUES (:bar);
$insert->column('bar', $bar_value, \PDO::PARAM_LOB);
```

You can set several placeholders and their corresponding values all at once by
using the `columns()` method:

```php
// INSERT INTO foo (bar) VALUES (:bar);
$insert->columns([
    'bar' => $bar_value,
    'baz' => $baz_value
]);
```

However, you will not be able to specify a particular PDO parameter type when
doing do.

Bound values are automatically quoted and escaped; in some cases, this will
be inappropriate, so you can use the `raw()` method to set column to an unquoted and
unescaped expression.

```
// INSERT INTO foo (bar) VALUES (NOW());
$insert->raw('bar', 'NOW()');
```

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $insert->perform(); // : PDOStatement
```
