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

use Atlas\Statement\Insert as InsertStatement;

class Insert extends InsertStatement
{
    use Query;

    public function getLastInsertId(string $name = null) : string
    {
        return $this->connection->lastInsertId($name);
    }
}
