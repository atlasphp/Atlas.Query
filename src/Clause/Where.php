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
    protected Component\Conditions $where;

    public function where(string $condition, mixed ...$bindInline) : static
    {
        $this->where->and($condition, ...$bindInline);
        return $this;
    }

    public function whereSprintf(string $format, mixed ...$bindInline) : static
    {
        $this->where->andSprintf($format, ...$bindInline);
        return $this;
    }

    public function andWhere(string $condition, mixed ...$bindInline) : static
    {
        $this->where->and($condition, ...$bindInline);
        return $this;
    }

    public function andWhereSprintf(string $format, mixed ...$bindInline) : static
    {
        $this->where->andSprintf($format, ...$bindInline);
        return $this;
    }

    public function orWhere(string $condition, mixed ...$bindInline) : static
    {
        $this->where->or($condition, ...$bindInline);
        return $this;
    }

    public function orWhereSprintf(string $format, mixed ...$bindInline) : static
    {
        $this->where->orSprintf($format, ...$bindInline);
        return $this;
    }

    public function catWhere(string $condition, mixed ...$bindInline) : static
    {
        $this->where->cat($condition, ...$bindInline);
        return $this;
    }

    public function catWhereSprintf(string $format, mixed ...$bindInline) : static
    {
        $this->where->catSprintf($format, ...$bindInline);
        return $this;
    }

    public function whereEquals(array $columnsValues) : static
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

    public function resetWhere() : static
    {
        $this->where = new Component\Conditions($this->bind, 'WHERE');
        return $this;
    }
}
