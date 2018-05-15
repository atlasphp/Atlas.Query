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

trait OrderBy
{
    protected $orderBy;

    public function orderBy(string $expr, string ...$exprs)
    {
        $this->orderBy->expr($expr, ...$exprs);
        return $this;
    }

    public function resetOrderBy()
    {
        $this->orderBy = new Component\By('ORDER');
        return $this;
    }
}
