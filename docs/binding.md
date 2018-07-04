# Value Binding

Atlas.Query allows you to bind values to the SQL statement in various ways.

## Implicit Inline Binding

Many Atlas.Query methods allow for inline binding of values. This means that the
provided value will be represented by an auto-generated placeholder name in the
query string, and the value itself will be retained for binding into that
placeholder at query execution time.

For example, given this query ...

```php
$select
    ->columns('*')
    ->from('foo')
    ->where('bar = ', $bar_value); // binds $bar_value inline
```

... a subsequent call to `getStatement()` will return:

```sql
SELECT *
FROM foo
WHERE bar = :__1__
```

(The auto-generated placeholder name will increment each time an inline value
gets bound.)

If `$bar_value` is `foo-bar`, calling `getBindValues()` will return:

```php
[
    ':__1__' => ['foo-bar', \PDO::PARAM_STR],
]
```

Note that the placeholder is automatically recognized as a string; the same will
be true for nulls, integers, and floats.

If you want to explicitly bind the value as some other type, you can pass that
type after the value:

```php
$select
    ->columns('*')
    ->from('foo')
    ->where('bar = ', $bar_value, \PDO::PARAM_LOB);
```

If you bind an array inline, Atlas.Query will set a bind each element separately
with its own placeholder, comma-separate the placeholders, and wrap them in
parentheses. This makes using an IN() condition very convenient.

```php
$bar_value = ['foo', 'bar', 'baz'];

// SELECT * FROM foo WHERE bar IN (:__1__, :__2__, :__3__)
$select
    ->columns('*')
    ->from('foo')
    ->where('bar IN ', $bar_value);
```

Finally, if the inline value is itself a Select object, it will be converted to
a string via `getStatement()` and returned surrounded in parentheses:

```php
// SELECT * FROM foo WHERE bar IN (SELECT baz FROM dib)
$select
    ->columns('*')
    ->from('foo')
    ->where('bar IN ', $select->subSelect()
        ->columns('baz')
        ->from('dib')
    );
```

## Explicit Inline Binding

If you need to bind more than one value into a condition, use `sprintf()`
combined with the `bindInline()` method:

```php
// SELECT * FROM foo WHERE bar BETWEEN :__1__ AND :__2__
$select
    ->columns('*')
    ->from('foo')
    ->where(sprintf(
        'bar BETWEEN %s AND %s',
        $select->bindInline($low_value),
        $select->bindInline($high_value)
    ));
```

## Explicit Parameter Binding

You can still use the normal PDO binding approach, where you explicitly set
named parameters in conditions, and then bind the values with a separate call:

```php
$select
    ->columns('*')
    ->from('foo')
    ->where('bar = :bar')
    ->orWhere('baz = :baz')
    ->bindValue('bar', $bar_value);
    ->bindValue('baz', $baz_value);
```

These too will automatically recognize strings, nulls, integers, and floats,
and set the approporate PDO parameter type. If you want to explicitly bind the
value as some other type, pass an option third parameter to `bindValue()`:

```php
$select
    ->columns('*')
    ->from('foo')
    ->where('bar = :bar')
    ->orWhere('baz = :baz')
    ->bindValue('bar', $bar_value, \PDO::PARAM_LOB);
    ->bindValue('baz', $baz_value);
```

You can also bind multiple values at once ...

```php
$select
    ->columns('*')
    ->from('foo')
    ->where('bar = :bar')
    ->orWhere('baz = :baz')
    ->bindValues([
        'bar' => $bar_value,
        'baz' => $baz_value
    );
```

... but in that case you will not be able to explicitly set the parameter types.

The automatic binding of array elements, as with implicit inline binding, **does
not work** with explicit parameter binding.
