# DELETE

## Building The Query

Build a _Delete_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

```php
$delete
    ->from('foo')                   // FROM this table
    ->where('zim = :zim')           // AND WHERE these conditions
    ->orWhere('gir = :gir')         // OR WHERE these conditions
    ->bindValue('bar', 'bar_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values to the query
        'baz' => 99,
        'zim' => 'dib',
        'gir' => 'doom',
    ]);
```

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $select->perform(); // : PDOStatement
```
