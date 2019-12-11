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

trait Having
{
    protected $having;

    public function having(string $condition, ...$bindInline)
    {
        $this->having->and($condition, ...$bindInline);
        return $this;
    }

    public function havingFormat(string $format, ...$bindInline)
    {
        $this->having->andFormat($format, ...$bindInline);
        return $this;
    }

    public function andHaving(string $condition, ...$bindInline)
    {
        $this->having->and($condition, ...$bindInline);
        return $this;
    }

    public function andHavingFormat(string $format, ...$bindInline)
    {
        $this->having->andFormat($format, ...$bindInline);
        return $this;
    }

    public function orHaving(string $condition, ...$bindInline)
    {
        $this->having->or($condition, ...$bindInline);
        return $this;
    }

    public function orHavingFormat(string $format, ...$bindInline)
    {
        $this->having->orFormat($format, ...$bindInline);
        return $this;
    }

    public function catHaving(string $condition, ...$bindInline)
    {
        $this->having->cat($condition, ...$bindInline);
        return $this;
    }

    public function catHavingFormat(string $format, ...$bindInline)
    {
        $this->having->andFormat($format, ...$bindInline);
        return $this;
    }

    public function resetHaving()
    {
        $this->having = new Component\Conditions($this->bind, 'HAVING');
        return $this;
    }
}
