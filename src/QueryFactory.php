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

    public function newDelete(Connection $connection, ...$args) : Delete
    {
        return $this->newQuery(Delete::CLASS, $connection, $args);
    }

    public function newInsert(Connection $connection, ...$args) : Insert
    {
        return $this->newQuery(Insert::CLASS, $connection, $args);
    }

    public function newSelect(Connection $connection, ...$args) : Select
    {
        return $this->newQuery(Select::CLASS, $connection, $args);
    }

    public function newUpdate(Connection $connection, ...$args) : Update
    {
        return $this->newQuery(Update::CLASS, $connection, $args);
    }

    protected function newQuery(
        string $class,
        Connection $connection,
        array $args
    ) : Query
    {
        return new $class($connection, $this->newBind(), ...$args);
    }
}
