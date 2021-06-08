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

class DeleteStatement extends Statement
{
    use Clause\Where;
    use Clause\OrderBy;
    use Clause\Limit;
    use Clause\Returning;

    protected string $table = '';

    public function from(string $table) : static
    {
        $this->table = $table;
        return $this;
    }

    public function getQueryString() : string
    {
        return $this->with->build()
            . 'DELETE'
            . $this->flags->build()
            . ' FROM ' . $this->table
            . $this->where->build()
            . $this->returning->build();
    }
}
