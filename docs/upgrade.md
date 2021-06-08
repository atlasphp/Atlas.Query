# Upgrade Notes

PHP 8.0 is now required, given the addition of expanded and stricter
typehinting.

Connectionless statement-only objects are now provided. Because of this, the
internal class hierarchy and naming has changed substantially.

When using a subselect as an inline value, you no longer need to call
`getStatement()`. Indeed, you *must* not, if you want the bound values to
transferred to the receiving query properly.

Generated placeholder tokens now have a different format; whereas in 1.x they
were composed of a single number (`:__1__`), they are now composed of two
numbers (`:_1_1_`). The first number corresponds to the number of query
instances; the second is the count of bound values on that query instance.
If you depend on the placeholder token format, e.g. in your tests, you will
need to update your expectations.
