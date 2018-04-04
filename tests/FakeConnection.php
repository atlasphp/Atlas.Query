<?php
namespace Atlas\Query;

use Atlas\Pdo\Connection;
use Generator;
use PDOStatement;

class FakeConnection extends Connection
{
    public function __construct(string $driver)
    {
        $this->driver = $driver;
    }

    public function getDriverName() : string
    {
        return $this->driver;
    }

    public function fetchAll(string $statement, array $values = []) : array
    {
        return [];
    }

    public function yieldAll(string $statement, array $values = []) : Generator
    {
        yield [];
    }

    public function perform(string $statement, array $values = []) : PDOStatement
    {
        return new PDOStatement();
    }

    public function lastInsertId(string $name = null) : string
    {
        return "{$name}-1";
    }
}
