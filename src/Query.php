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
use Atlas\Query\Quoter\Quoter;
use Atlas\Query\Clause\Component\Flags;
use Atlas\Query\Clause\Component\With;
use PDOStatement;

abstract class Query
{
    protected Bind $bind;

    protected Connection $connection;

    protected Flags $flags;

    protected Quoter $quoter;

    protected With $with;

    static public function new($arg, ...$args) : static
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

        $quoter = 'Atlas\\Query\\Quoter\\'
            . ucfirst($this->connection->getDriverName())
            . 'Quoter';
        $this->quoter = new $quoter();

        $this->reset();
    }

    public function perform() : PDOStatement
    {
        return $this->connection->perform(
            $this->getStatement(),
            $this->getBindValues()
        );
    }

    public function bindInline($value, int $type = -1) : string
    {
        return $this->bind->inline($value, $type);
    }

    public function bindSprintf(string $format, mixed ...$values) : string
    {
        return $this->bind->sprintf($format, ...$values);
    }

    public function bindValue(string $key, $value, int $type = -1) : static
    {
        $this->bind->value($key, $value, $type);
        return $this;
    }

    public function bindValues(array $values) : static
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

    public function reset() : static
    {
        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 5) == 'reset' && $method != 'reset') {
                $this->$method();
            }
        }
        return $this;
    }

    public function resetFlags() : static
    {
        $this->flags = new Clause\Component\Flags();
        return $this;
    }

    public function resetWith() : static
    {
        $this->with = new Clause\Component\With($this->bind, $this->quoter);
        return $this;
    }

    public function with(string $cteName, array $cteColumns, string|Query $cteQuery) : static
    {
        $this->with->setCte($cteName, $cteColumns, $cteQuery);
        return $this;
    }

    public function withRecursive(bool $recursive = true) : static
    {
        $this->with->setRecursive($recursive);
        return $this;
    }

    public function quoteIdentifier(string $name) : string
    {
        return $this->quoter->quoteIdentifier($name);
    }

    /**
     * @todo allow for an indent level
     */
    abstract public function getStatement() : string;
}
