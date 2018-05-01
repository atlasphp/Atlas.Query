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
    protected $selectClass;

    public function __construct($selectClass = Select::CLASS)
    {
        $this->selectClass = $selectClass;
    }

    public function newBind() : Bind
    {
        return new Bind();
    }

    public function newDelete(Connection $connection) : Delete
    {
        return new Delete($connection, $this->newBind());
    }

    public function newInsert(Connection $connection) : Insert
    {
        return new Insert($connection, $this->newBind());
    }

    public function newSelect(Connection $connection) : Select
    {
        $selectClass = $this->selectClass;
        return new $selectClass($connection, $this->newBind());
    }

    public function newUpdate(Connection $connection) : Update
    {
        return new Update($connection, $this->newBind());
    }
}
