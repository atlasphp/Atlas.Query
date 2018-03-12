<?php
declare(strict_types=1);

/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

class Update extends Query
{
    use Clause\Where;
    use Clause\ModifyColumns;
    use Clause\Returning;

    protected $table;

    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function getStatement() : string
    {
        return 'UPDATE'
            . $this->flags->build()
            . ' ' . $this->table
            . $this->columns->build()
            . $this->where->build()
            . $this->returning->build();
    }
}
