<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Statement\Clause;

trait ModifyColumns
{
    protected Component\ModifyColumns $columns;

    public function column(string $column, mixed ...$value) : static
    {
        $this->columns->hold($column, ...$value);
        return $this;
    }

    public function columns(array $columns) : static
    {
        foreach ($columns as $key => $val) {
            if (is_int($key)) {
                $this->column($val);
            } else {
                $this->column($key, $val);
            }
        }

        return $this;
    }

    public function set(string $column, mixed $value) : static
    {
        $this->columns->raw($column, $value);
        return $this;
    }

    public function hasColumns() : bool
    {
        return $this->columns->hasAny();
    }

    public function resetColumns() : static
    {
        $type = substr(
            (string) strrchr(static::CLASS, '\\'),
            1,
            6
        );
        $class = __NAMESPACE__ . "\\Component\\{$type}Columns";
        $this->columns = new $class($this->bind, $this->driver);
        return $this;
    }
}
