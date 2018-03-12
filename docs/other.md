# Other Topics

## Microsoft SQL Server Considerations

If the Atlas.Pdo _Connection_ is to a Microsoft SQL Server ('sqlsrv') instance,
the LIMIT-related methods on the query object will generate sqlsrv-specific
variations of `LIMIT ... OFFSET`:

- If only a `LIMIT` is present, it will be translated as a `TOP` clause.

- If both `LIMIT` and `OFFSET` are present, it will be translated as an
  `OFFSET ... ROWS FETCH NEXT ... ROWS ONLY` clause. In this case there *must*
  be an `ORDER BY` clause, as the limiting clause is a sub-clause of `ORDER
  BY`.

## PostgreSQL Considerations

The _Insert_, _Update_, and _Delete_ methods expose a `returning()` method that
sets a RETURNING clause on the query. This clause will be honored by Postgres
but will cause errors on other database backends.


## Identifier Quoting

Table and column identifiers will *not* be quoted automatically. If you need
quotes around unusual or reserved identifiers, you will need to add them
yourself.

## Table Prefixes

One frequently-requested feature for this package is support for "automatic
table prefixes" on all queries.  This feature sounds great in theory, but in
practice is it (1) difficult to implement well, and (2) even when implemented it
turns out to be not as great as it seems in theory. This assessment is the
result of the hard trials of experience. For those of you who want modifiable
table prefixes, we suggest using constants with your table names prefixed as
desired; as the prefixes change, you can then change your constants.
