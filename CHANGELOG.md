# Change Log

## 1.3.2

- Fixed #13, #14: handle empty array value in whereEquals()

- Fixed #17: add LIMIT/OFFSET on UPDATE and DELETE

- Updated docs and tests.

## 1.3.1

Hygiene release to update docs.

## 1.3.0

- Fixed `SELECT ... UNION` bug revealed in testing (refs #6).

- Added sprintf-based inline value binding.

- Updated docs and tests.

- Added Travis CI.

## 1.2.0

- Method `quoteIdentifier()` added to all query objects, so that you can quote
  table names, column names, etc. for the specific connection type.

- INSERT and UPDATE queries now automatically quote the column name that is
  being inserted or updated. No other automatic quoting is applied.

- Updated docs.

## 1.1.0

- Updated docs.

- ModifyColumns::column() now correctly returns $this (refs #1).

- Added Where::whereEquals() functionality (refs #1).

- Binding a Select inline now correctly calls getStatement() on it.

## 1.0.0

Changes to method parameter names, to make IDE completion more intelligible.

## 1.0.0-beta2

The QueryFactory now allows for alternative Select classes.

## 1.0.0-beta1

Add static `new()` method on queries, and update docs.

## 1.0.0-alpha1

First alpha release.
