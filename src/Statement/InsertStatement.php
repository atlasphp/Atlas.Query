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

class InsertStatement extends Statement
{
    use Clause\ModifyColumns;
    use Clause\Returning;

    protected string $table = '';

    public function into(string $table) : static
    {
        $this->table = $table;
        return $this;
    }

    public function getStatement() : string
    {
        return $this->with->build()
            . 'INSERT'
            . $this->flags->build()
            . " INTO {$this->table} "
            . $this->columns->build()
            . $this->returning->build();
    }
}
