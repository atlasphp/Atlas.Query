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

class SelectColumns extends Component
{
    protected $list = [];

    public function add(string $expr, string ...$exprs) : void
    {
        $this->list[] = $expr;
        foreach ($exprs as $expr) {
            $this->list[] = $expr;
        }
    }

    public function hasAny() : bool
    {
        return ! empty($this->list);
    }

    public function build() : string
    {
        return $this->indentCsv($this->list);
    }
}
