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

trait Limit
{
    protected $limit;

    public function limit(int $limit)
    {
        $this->limit->setLimit($limit);
        return $this;
    }

    public function offset(int $offset)
    {
        $this->limit->setOffset($offset);
        return $this;
    }

    public function page(int $page)
    {
        $this->limit->setPage($page);
        return $this;
    }

    public function perPage(int $perPage)
    {
        $this->limit->setPerPage($perPage);
        return $this;
    }

    public function resetLimit()
    {
        if ($this->connection->getDriverName() == 'sqlsrv') {
            $this->limit = new Component\LimitSqlsrv();
        } else {
            $this->limit = new Component\Limit();
        }

        return $this;
    }
}
