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

use Atlas\Query\Statement\Clause\Component\LimitSqlsrv;

class SqlsrvDriver extends Driver
{
    public function getLimitClass() : string
    {
        return LimitSqlsrv::CLASS;
    }

    public function quoteIdentifier(string $name) : string
    {
        return "[{$name}]";
    }
}
