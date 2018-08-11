# Atlas.Query

Provides query builders for MySQL, Postgres, SQLite, and Microsoft SQL Server
backends connected via [Atlas.Pdo][].

Read the documentation [here](http://atlasphp.io/cassini/query).

## Lineage

This package is a descendant of [Aura.SqlQuery][] but differs from that package
in significant ways. Some of those differences include:

- The query objects depend on an Atlas.Pdo _Connection_ (which can wrap a regular
  _PDO_ instance).

- There are no database-specific query classes as with the Aura package, though
  some database-specific behaviors are provided. This means the query object can
  be more reliably typehinted and provide IDE auto-completion, but at the cost
  of providing some methods that some databases may not   recognize.

- The _Insert_ class provides only single-row inserts, not multiple-row.

- Binding of values into Atlas queries allows for different PDO parameter
  types.

- Some method names have changed slightly; for example, `cols()` is now
  `columns()`. Likewise, some method signatures have changed; for example,
  whereas in Aura.SqlQuery a method might take an array of expressions,
  the equivalent Atlas.Query method may use variadic arguments instead.

[Atlas.Pdo]: https://github.com/atlasphp/Atlas.Pdo
[Aura.SqlQuery]: https://github.com/auraphp/Aura.SqlQuery
