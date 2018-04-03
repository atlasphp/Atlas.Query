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

class Insert extends Query
{
    use Clause\ModifyColumns;
    use Clause\Returning;

    protected $into = '';

    public function into(string $into)
    {
        $this->into = $into;
        return $this;
    }

    public function getStatement() : string
    {
        return 'INSERT'
            . $this->flags->build()
            . " INTO {$this->into} "
            . $this->columns->build()
            . $this->returning->build();
    }

    public function getLastInsertId($name = null)
    {
        return $this->connection->getPdo()->lastInsertId($name);
    }
}
