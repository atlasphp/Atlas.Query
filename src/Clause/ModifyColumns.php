<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Clause;

trait ModifyColumns
{
    protected $columns;

    public function column(string $column, ...$value)
    {
        $this->columns->hold($column, ...$value);
        return $this;
    }

    public function columns(array $columns)
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

    public function set(string $column, $value)
    {
        $this->columns->raw($column, $value);
        return $this;
    }

    public function hasColumns() : bool
    {
        return $this->columns->hasAny();
    }

    public function resetColumns()
    {
        $type = strrchr(static::CLASS, '\\') . 'Columns';
        $class = __NAMESPACE__ . '\\Component' . $type;
        $this->columns = new $class($this->bind, $this->quoter);
        return $this;
    }
}
