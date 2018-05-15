<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Clause;

trait SelectColumns
{
    protected $columns;

    public function columns(string $expr, string ...$exprs)
    {
        $this->columns->add($expr, ...$exprs);
        return $this;
    }

    public function resetColumns()
    {
        $this->columns = new Component\SelectColumns();
        return $this;
    }

    public function hasColumns()
    {
        return $this->columns->hasAny();
    }
}
