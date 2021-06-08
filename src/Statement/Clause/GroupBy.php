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

trait GroupBy
{
    protected Component\By $groupBy;

    public function resetGroupBy() : static
    {
        $this->groupBy = new Component\By('GROUP');
        return $this;
    }

    public function groupBy(string $expr, string ...$exprs) : static
    {
        $this->groupBy->expr($expr, ...$exprs);
        return $this;
    }
}
