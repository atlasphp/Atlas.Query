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

class Delete extends Query
{
    use Clause\Where;
    use Clause\OrderBy;
    use Clause\Limit;
    use Clause\Returning;

    protected $from = '';

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function getStatement() : string
    {
        return 'DELETE'
            . $this->flags->build()
            . ' FROM ' . $this->from
            . $this->where->build()
            . $this->returning->build();
    }
}
