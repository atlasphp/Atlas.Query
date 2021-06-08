<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query;

use Atlas\Query\Statement\InsertStatement;

class Insert extends InsertStatement
{
    use Query;

    public function getLastInsertId(string $name = null) : string
    {
        return $this->connection->lastInsertId($name);
    }
}
