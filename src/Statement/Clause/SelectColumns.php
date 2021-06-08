<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Statement\Clause;

trait SelectColumns
{
    protected Component\SelectColumns $columns;

    public function columns(string $expr, string ...$exprs) : static
    {
        $this->columns->add($expr, ...$exprs);
        return $this;
    }

    public function resetColumns() : static
    {
        $this->columns = new Component\SelectColumns();
        return $this;
    }

    public function hasColumns() : bool
    {
        return $this->columns->hasAny();
    }
}
