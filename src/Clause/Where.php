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

trait Where
{
    protected $where;

    public function where(string $condition, ...$bindInline)
    {
        $this->where->and($condition, ...$bindInline);
        return $this;
    }

    public function andWhere(string $condition, ...$bindInline)
    {
        $this->where->and($condition, ...$bindInline);
        return $this;
    }

    public function orWhere(string $condition, ...$bindInline)
    {
        $this->where->or($condition, ...$bindInline);
        return $this;
    }

    public function catWhere(string $condition, ...$bindInline)
    {
        $this->where->cat($condition, ...$bindInline);
        return $this;
    }

    public function whereEquals(array $columnsValues)
    {
        foreach ($columnsValues as $key => $val) {
            if (is_numeric($key)) {
                $this->where($val);
            } elseif ($val === null) {
                $this->where("{$key} IS NULL");
            } elseif (is_array($val)) {
                $this->where("{$key} IN ", $val);
            } else {
                $this->where("{$key} = ", $val);
            }
        }

        return $this;
    }

    public function resetWhere()
    {
        $this->where = new Component\Conditions($this->bind, 'WHERE');
        return $this;
    }
}
