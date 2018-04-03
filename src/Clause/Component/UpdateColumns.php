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

class UpdateColumns extends ModifyColumns
{
    public function build() : string
    {
        $values = array();
        foreach ($this->list as $column => $value) {
            $values[] = "{$column} = {$value}";
        }
        return PHP_EOL . 'SET' . $this->indentCsv($values);
    }
}
