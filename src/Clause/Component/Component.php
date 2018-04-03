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

abstract class Component
{
    public function indentCsv(array $list) : string
    {
        return PHP_EOL . '    '
             . implode(',' . PHP_EOL . '    ', $list);
    }

    public function indent(array $list) : string
    {
        if (empty($list)) {
            return '';
        }

        return PHP_EOL . '    '
             . implode(PHP_EOL . '    ', $list);
    }

    abstract public function build() : string;
}
