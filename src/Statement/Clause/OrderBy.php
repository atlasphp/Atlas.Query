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

trait OrderBy
{
    protected Component\By $orderBy;

    public function orderBy(string $expr, string ...$exprs) : static
    {
        $this->orderBy->expr($expr, ...$exprs);
        return $this;
    }

    public function resetOrderBy() : static
    {
        $this->orderBy = new Component\By('ORDER');
        return $this;
    }
}
