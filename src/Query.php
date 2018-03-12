<?php
declare(strict_types=1);

/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

use Atlas\Pdo\Connection;

abstract class Query
{
    protected $bind;

    protected $connection;

    protected $flags;

    public function __construct(Connection $connection, Bind $bind)
    {
        $this->connection = $connection;
        $this->bind = $bind;
        $this->reset();
    }

    public function perform()
    {
        return $this->connection->perform(
            $this->getStatement(),
            $this->getBindValues()
        );
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function bindValue(string $key, $value, int $type = -1)
    {
        $this->bind->value($key, $value, $type);
        return $this;
    }

    public function bindValues(array $values)
    {
        $this->bind->values($values);
        return $this;
    }

    public function getBindValues()
    {
        return $this->bind->getCopy();
    }

    public function reset()
    {
        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 5) == 'reset' && $method != 'reset') {
                $this->$method();
            }
        }
        return $this;
    }

    public function resetFlags()
    {
        $this->flags = new Clause\Component\Flags();
        return $this;
    }

    abstract public function getStatement() : string;
}
