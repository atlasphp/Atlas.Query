<?php
declare(strict_types=1);

/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query\Clause;

trait Having
{
    protected $having;

    public function having(string $condition, ...$inline)
    {
        $this->having->and($condition, ...$inline);
        return $this;
    }

    public function andHaving(string $condition, ...$inline)
    {
        $this->having->and($condition, ...$inline);
        return $this;
    }

    public function orHaving(string $condition, ...$inline)
    {
        $this->having->or($condition, ...$inline);
        return $this;
    }

    public function catHaving(string $condition, ...$inline)
    {
        $this->having->cat($condition, ...$inline);
        return $this;
    }

    public function resetHaving()
    {
        $this->having = new Component\Conditions($this->bind, 'HAVING');
        return $this;
    }
}
