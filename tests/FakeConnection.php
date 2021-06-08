<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

use Atlas\Pdo\Connection;
use Generator;
use PDOStatement;

class FakeConnection extends Connection
{
    private $driverName;

    public function __construct(string $driverName)
    {
        $this->driverName = $driverName;
    }

    public function getDriverName() : string
    {
        return $this->driverName;
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
