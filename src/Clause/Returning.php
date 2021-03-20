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

trait Returning
{
    protected Component\ReturnColumns $returning;

    public function returning(string $expr, string ...$exprs) : static
    {
        $this->returning->add($expr, ...$exprs);
        return $this;
    }

    public function resetReturning() : static
    {
        $this->returning = new Component\ReturnColumns();
        return $this;
    }
}
