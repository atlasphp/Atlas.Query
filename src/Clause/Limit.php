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
    protected Component\Limit $limit;

    public function limit(int $limit) : static
    {
        $this->limit->setLimit($limit);
        return $this;
    }

    public function offset(int $offset) : static
    {
        $this->limit->setOffset($offset);
        return $this;
    }

    public function page(int $page) : static
    {
        $this->limit->setPage($page);
        return $this;
    }

    public function perPage(int $perPage) : static
    {
        $this->limit->setPerPage($perPage);
        return $this;
    }

    public function resetLimit() : static
    {
        $limit = $this->driver->getLimitClass();
        $this->limit = new $limit();
        return $this;
    }
}
