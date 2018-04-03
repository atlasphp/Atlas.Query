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

class ReturnColumns extends SelectColumns
{
    public function build() : string
    {
        if (empty($this->list)) {
            return '';
        }

        return PHP_EOL . 'RETURNING' . $this->indentCsv($this->list);
    }
}
