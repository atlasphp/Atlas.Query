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

trait GroupBy
{
    protected $groupBy;

    public function resetGroupBy()
    {
        $this->groupBy = new Component\By('GROUP');
        return $this;
    }

    public function groupBy(string $expr, string ...$exprs)
    {
        $this->groupBy->expr($expr, ...$exprs);
        return $this;
    }
}
