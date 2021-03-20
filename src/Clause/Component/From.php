<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Clause\Component;

use Atlas\Query\Bind;
use Atlas\Query\Select;

class From extends Component
{
    protected Bind $bind;

    protected array $list = [];

    public function __construct(Bind $bind)
    {
        $this->bind = $bind;
    }

    public function table(string|Select $ref) : void
    {
        if ($ref instanceof Select) {
            $this->bind->merge($ref->getBindValues());
            $ref = $ref->getStatement();
        }

        $this->list[] = [$ref];
    }

    public function join(
        string $join,
        string $ref,
        string|Select $condition = '',
        mixed ...$bindInline
    ) : void
    {
        if ($condition instanceof Select) {
            $this->bind->merge($condition->getBindValues());
            $condition = $condition->getStatement();
        }

        $condition = ltrim($condition);

        if (
            $condition !== ''
            && strtoupper(substr($condition, 0, 3)) !== 'ON '
            && strtoupper(substr($condition, 0, 6)) !== 'USING '
        ) {
            $condition = 'ON ' . $condition;
        }

        if (! empty($bindInline)) {
            $condition .= $this->bind->inline(...$bindInline);
        }

        end($this->list);
        $end = key($this->list);
        $this->list[$end][] = "    {$join} {$ref} {$condition}";
    }

    public function catJoin(string $expr, mixed ...$bindInline) : void
    {
        if (! empty($bindInline)) {
            $expr .= $this->bind->inline(...$bindInline);
        }

        end($this->list);
        $end = key($this->list);
        end($this->list[$end]);
        $key = key($this->list[$end]);
        $this->list[$end][$key] .= $expr;
    }

    public function build() : string
    {
        if (empty($this->list)) {
            return '';
        }

        $from = [];

        foreach ($this->list as $list) {
            $from[] = array_shift($list) . $this->indent($list);
        }

        return PHP_EOL . 'FROM' . $this->indentCsv($from);
    }
}
