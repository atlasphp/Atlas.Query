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

class UpdateStatement extends Statement
{
    use Clause\ModifyColumns;
    use Clause\Where;
    use Clause\OrderBy;
    use Clause\Limit;
    use Clause\Returning;

    protected string $table = '';

    public function table(string $table) : static
    {
        $this->table = $table;
        return $this;
    }

    public function getQueryString() : string
    {
        return $this->with->build()
            . 'UPDATE'
            . $this->flags->build()
            . ' ' . $this->table
            . $this->columns->build()
            . $this->where->build()
            . $this->returning->build();
    }
}
