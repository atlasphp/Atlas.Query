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

use Atlas\Query\Clause\Component\Limit;

abstract class Driver
{
    public function getLimitClass() : string
    {
        return Limit::CLASS;
    }

    abstract public function quoteIdentifier(string $name) : string;
}
