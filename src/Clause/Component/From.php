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

class From extends Component
{
    protected $bind;

    protected $list = [];

    public function __construct(Bind $bind)
    {
        $this->bind = $bind;
    }

    public function table(string $ref) : void
    {
        $this->list[] = [$ref];
    }

    public function join(string $join, string $ref, string $condition = '', ...$bindInline) : void
    {
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

    public function catJoin(string $expr, ...$bindInline)
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
