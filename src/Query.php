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
use Atlas\Query\Driver\Driver;
use PDOStatement;

trait Query
{
    static public function new(mixed $arg, mixed ...$args) : static
    {
        if ($arg instanceof Connection) {
            $connection = $arg;
        } else {
            $connection = Connection::new($arg, ...$args);
        }

        $driver = 'Atlas\\Query\\Driver\\'
            . ucfirst($connection->getDriverName())
            . 'Driver';

        return new static(new $driver(), $connection);
    }

    protected Connection $connection;

    public function __construct(
        Driver $driver,
        Connection $connection,
    ) {
        parent::__construct($driver);
        $this->connection = $connection;
    }

    public function perform() : PDOStatement
    {
        return $this->connection->perform(
            $this->getQueryString(),
            $this->getBindValues()
        );
    }
}
