# Upgrade Notes

The primary difference from 1.x requires PHP 8.0, given the addition of
expanded and stricter typehinting in 2.x.

Generated placeholder tokens now have a different format; whereas in 1.x they
were composed of a single number (`:__1__`), they are now composed of two
numbers (`:_1_1_`). The first number corresponds to the number of query
instances; the second is the count of bound values on that query instance.
If you depend on the placeholder token format, e.g. in your tests, you will
need to update your expectations.

The 2.x series supports Common Table Expressions, whereas 1.x does not.

When using a subselect as an inline value, you no longer need to call
getStatement(); indeed, you *must* not, if you want the bound values to
transferred to the receiving query properly.

The `Quoter` classes have been renamed to `Driver`.
