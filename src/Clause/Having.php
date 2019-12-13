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

    public function havingSprintf(string $format, ...$bindInline)
    {
        $this->having->andSprintf($format, ...$bindInline);
        return $this;
    }

    public function andHaving(string $condition, ...$bindInline)
    {
        $this->having->and($condition, ...$bindInline);
        return $this;
    }

    public function andHavingSprintf(string $format, ...$bindInline)
    {
        $this->having->andSprintf($format, ...$bindInline);
        return $this;
    }

    public function orHaving(string $condition, ...$bindInline)
    {
        $this->having->or($condition, ...$bindInline);
        return $this;
    }

    public function orHavingSprintf(string $format, ...$bindInline)
    {
        $this->having->orSprintf($format, ...$bindInline);
        return $this;
    }

    public function catHaving(string $condition, ...$bindInline)
    {
        $this->having->cat($condition, ...$bindInline);
        return $this;
    }

    public function catHavingSprintf(string $format, ...$bindInline)
    {
        $this->having->catSprintf($format, ...$bindInline);
        return $this;
    }

    public function resetHaving()
    {
        $this->having = new Component\Conditions($this->bind, 'HAVING');
        return $this;
    }
}
