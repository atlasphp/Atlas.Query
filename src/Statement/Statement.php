<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Statement;

use Atlas\Query\Driver\Driver;
use Atlas\Query\Statement\Clause\Component\Flags;
use Atlas\Query\Statement\Clause\Component\With;

abstract class Statement
{
    static public function new(string $driverName) : static
    {
        $driver = 'Atlas\\Query\\Driver\\'
            . $driverName
            . 'Driver';

        return new static(new $driver());
    }

    protected Driver $driver;

    protected Bind $bind;

    protected Flags $flags;

    protected With $with;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
        $this->bind = new Bind();
        $this->reset();
    }

    public function __clone()
    {
        $vars = get_object_vars($this);

        foreach ($vars as $name => $prop) {
            if (is_object($prop)) {
                $this->$name = clone $prop;
            }
        }
    }

    public function bindInline(mixed $value, int $type = -1) : string
    {
        return $this->bind->inline($value, $type);
    }

    public function bindSprintf(string $format, mixed ...$values) : string
    {
        return $this->bind->sprintf($format, ...$values);
    }

    public function bindValue(string $key, mixed $value, int $type = -1) : static
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
        $this->flags = new Flags();
        return $this;
    }

    public function resetWith() : static
    {
        $this->with = new With($this->bind, $this->driver);
        return $this;
    }

    public function with(string $cteName, string|Statement $cteQuery) : static
    {
        $this->with->setCte($cteName, [], $cteQuery);
        return $this;
    }

    public function withColumns(string $cteName, array $cteColumns, string|Statement $cteQuery) : static
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
        return $this->driver->quoteIdentifier($name);
    }

    abstract public function getStatement() : string;
}
