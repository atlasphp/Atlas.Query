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
use BadMethodCallException;

class NullConnection extends Connection
{
    protected $driverName;

    public static function new(...$args) : Connection
    {
        return new static(...$args);
    }

    public function __construct(string $driverName = 'null')
    {
        $this->driverName = $driverName;
    }

    public function __call(
        string $method,
        array $params
    ) {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function getDriverName() : string
    {
        return $this->driverName;
    }

    public function getPdo() : PDO
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function beginTransaction() : bool
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function commit() : bool
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function rollBack() : bool
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function exec(string $statement) : int
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function prepare(
        string $statement,
        array $driverOptions = []
    ) : PDOStatement
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function perform(
        string $statement,
        array $values = []
    ) : PDOStatement
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function query(string $statement, ...$fetch) : PDOStatement
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchAffected(
        string $statement,
        array $values = []
    ) : int
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchAll(
        string $statement,
        array $values = []
    ) : array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchUnique(
        string $statement,
        array $values = []
    ) : array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchColumn(
        string $statement,
        array $values = [],
        int $column = 0
    ) : array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchGroup(
        string $statement,
        array $values = [],
        int $style = PDO::FETCH_COLUMN
    ) : array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchObject(
        string $statement,
        array $values = [],
        string $class = 'stdClass',
        array $ctorArgs = []
    ) {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchObjects(
        string $statement,
        array $values = [],
        string $class = 'stdClass',
        array $ctorArgs = []
    ) : array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchOne(
        string $statement,
        array $values = []
    ) : ?array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchKeyPair(
        string $statement,
        array $values = []
    ) : array
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function fetchValue(
        string $statement,
        array $values = [],
        int $column = 0
    ) {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function yieldAll(
        string $statement,
        array $values = []
    ) : Generator
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function yieldUnique(
        string $statement,
        array $values = []
    ) : Generator
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function yieldColumn(
        string $statement,
        array $values = [],
        int $column = 0
    ) : Generator
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function yieldObjects(
        string $statement,
        array $values = [],
        string $class = 'stdClass',
        array $ctorArgs = []
    ) : Generator
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function yieldKeyPair(
        string $statement,
        array $values = []
    ) : Generator
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function logQueries(bool $logQueries = true) : void
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function getQueries()
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    public function setQueryLogger(callable $queryLogger) : void
    {
        throw $this->badMethodCallException(__METHOD__);
    }

    protected function badMethodCallException($method)
    {
        return new BadMethodCallException("{$method}() not available");
    }
}
