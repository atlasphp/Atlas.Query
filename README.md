# Atlas.Query

Provides query builders for MySQL, Postgres, SQLite, and Microsoft SQL Server
backends connected via [Atlas.Pdo][].

Read the documentation [here](./docs/index.md).

## Lineage

This package is a descendant of [Aura.SqlQuery](https://github.com/auraphp/Aura.SqlQuery)but differs from that package in significant ways. Some of those differences include:

- The query objects depend on an Atlas.Pdo _Connection_, and are not useful
  with a generic _PDO_ instance.

- There are no database-specific query classes as with the Aura package, though
  some database-specific behaviors are provided. This is so that the query
  object can be more reliably typehinted and provide IDE auto-completion.

- The _Insert_ class provides only single-row inserts, not multiple-row.

- Binding of values into Atlas queries allows for different PDO parameter
  types.

[Atlas.Pdo]: https://github.com/atlasphp/Atlas.Pdo
