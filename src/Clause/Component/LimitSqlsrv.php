<?php
declare(strict_types=1);

/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query\Clause\Component;

class LimitSqlsrv extends Limit
{
    public function buildEarly() : string
    {
        if ($this->limit > 0 && $this->offset == 0) {
            return " TOP {$this->limit}";
        }

        return '';
    }

    public function build() : string
    {
        if ($this->limit > 0 && $this->offset > 0) {
            return PHP_EOL
                . "OFFSET {$this->offset} ROWS "
                . "FETCH NEXT {$this->limit} ROWS ONLY";
        }

        return '';
    }
}
