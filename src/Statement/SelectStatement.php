<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Statement;

use Atlas\Query\Statement\Clause\Component\From;

class SelectStatement extends Statement
{
    use Clause\SelectColumns;
    use Clause\Where;
    use Clause\GroupBy;
    use Clause\Having;
    use Clause\OrderBy;
    use Clause\Limit;

    protected ?string $as = null;

    protected From $from;

    protected array $unions = [];

    protected bool $forUpdate = false;

    public function forUpdate(bool $enable = true) : static
    {
        $this->forUpdate = $enable;
        return $this;
    }

    public function distinct(bool $enable = true) : static
    {
        $this->flags->set('DISTINCT', $enable);
        return $this;
    }

    public function from(string|Statement $ref) : static
    {
        $this->from->table($ref);
        return $this;
    }

    public function join(
        string $join,
        string|Statement $ref,
        string $condition = '',
        mixed ...$bindInline
    ) : static
    {
        $join = strtoupper(trim($join));

        if (substr($join, -4) != 'JOIN') {
            $join .= ' JOIN';
        }

        $this->from->join($join, $ref, $condition, ...$bindInline);
        return $this;
    }

    public function catJoin(string $expr, mixed ...$bindInline) : static
    {
        $this->from->catJoin($expr, ...$bindInline);
        return $this;
    }

    public function union() : static
    {
        $this->unions[] = $this->getCurrentStatement(
            PHP_EOL . 'UNION' . PHP_EOL
        );
        $this->reset();
        return $this;
    }

    public function unionAll() : static
    {
        $this->unions[] = $this->getCurrentStatement(
            PHP_EOL . 'UNION ALL' . PHP_EOL
        );
        $this->reset();
        return $this;
    }

    public function as(string $as) : static
    {
        $this->as = $as;
        return $this;
    }

    public function resetFrom() : static
    {
        $this->from = new Clause\Component\From($this->bind);
        return $this;
    }

    public function resetAs() : static
    {
        $this->as = null;
        return $this;
    }

    public function subSelect() : static
    {
        $clone = clone $this;
        $clone->reset();
        $clone->bind->reset();
        return $clone;
    }

    public function getQueryString() : string
    {
        return implode('', $this->unions) . $this->getCurrentStatement();
    }

    protected function getCurrentStatement(string $suffix = '') : string
    {
        $stm = $this->with->build()
            . 'SELECT'
            . $this->flags->build()
            . $this->limit->buildEarly()
            . $this->columns->build()
            . $this->from->build()
            . $this->where->build()
            . $this->groupBy->build()
            . $this->having->build()
            . $this->orderBy->build()
            . $this->limit->build()
            . ($this->forUpdate ? PHP_EOL . 'FOR UPDATE' : '');

        if ($this->as !== null) {
            $stm = "(" . PHP_EOL . $stm . PHP_EOL . ") AS {$this->as}";
        }

        return $stm . $suffix;
    }
}
