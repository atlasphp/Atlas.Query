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

use Atlas\Pdo\Connection;

class QueryFactory
{
    public function newBind() : Bind
    {
        return new Bind();
    }

    public function newDelete(Connection $connection) : Delete
    {
        return $this->newQuery('Delete', $connection);
    }

    public function newInsert(Connection $connection) : Insert
    {
        return $this->newQuery('Insert', $connection);
    }

    public function newSelect(Connection $connection) : Select
    {
        return $this->newQuery('Select', $connection);
    }

    public function newUpdate(Connection $connection) : Update
    {
        return $this->newQuery('Update', $connection);
    }

    protected function newQuery(string $query, Connection $connection) : Query
    {
        $class = 'Atlas\Query\\' . $query;
        return new $class($connection, $this->newBind());
    }
}
