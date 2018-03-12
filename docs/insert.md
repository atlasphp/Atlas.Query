# INSERT

## Building The Query

Build an _Insert_ query using the following methods. They do not need to
be called in any particular order, and may be called multiple times.

```php
$insert
    ->into('foo')                   // INTO this table
    ->columns([                        // bind values as "(col) VALUES (:col)"
        'bar',
        'baz',
    ])
    ->rawColumn('ts', 'NOW()')            // raw value as "(ts) VALUES (NOW())"
    ->bindValue('foo', 'foo_val')   // bind one value to a placeholder
    ->bindValues([                  // bind these values
        'bar' => 'foo',
        'baz' => 'zim',
    ]);
```

The `set()` method allows you to pass an array of key-value pairs where the
key is the column name and the value is a bind value (not a raw value):

```php
$insert
    ->into('foo')             // insert into this table
    ->set([                     // insert these columns and bind these values
        'foo' => 'foo_value',
        'bar' => 'bar_value',
        'baz' => 'baz_value',
    ]);
```

## Performing The Query

Once you have built the query, call the `perform()` method to execute it and
get back a _PDOStatement_.

```php
$result = $select->perform(); // : PDOStatement
```
