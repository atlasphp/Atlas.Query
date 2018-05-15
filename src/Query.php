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

abstract class Query
{
    protected $bind;

    protected $connection;

    protected $flags;

    static public function new($arg, ...$args)
    {
        if ($arg instanceof Connection) {
            $connection = $arg;
        } else {
            $connection = Connection::new($arg, ...$args);
        }

        return new static($connection, new Bind());
    }

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

    public function bindInline($value, int $type = -1)
    {
        return $this->bind->inline($value, $type);
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

    public function getBindValues() : array
    {
        return $this->bind->getArrayCopy();
    }

    public function setFlag(string $flag, bool $enable = true) : void
    {
        $this->flags->set($flag, $enable);
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
