<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Driver;

class PgsqlDriver extends Driver
{
    public function quoteIdentifier(string $name) : string
    {
        return '"' . $name . '"';
    }
}
