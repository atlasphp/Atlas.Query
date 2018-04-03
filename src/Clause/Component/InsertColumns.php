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

class InsertColumns extends ModifyColumns
{
    public function build() : string
    {
        return '('
            . $this->indentCsv(array_keys($this->list))
            . PHP_EOL . ') VALUES ('
            . $this->indentCsv(array_values($this->list))
            . PHP_EOL . ')';

    }
}
