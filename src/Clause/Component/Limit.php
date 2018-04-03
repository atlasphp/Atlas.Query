<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Clause\Component;

class Limit
{
    protected $limit = 0;

    protected $offset = 0;

    protected $page = 0;

    protected $perPage = 10;

    public function setLimit(int $limit) : void
    {
        $this->limit = $limit;
        if ($this->page) {
            $this->page = 0;
            $this->offset = 0;
        }
    }

    public function getLimit() : int
    {
        return $this->limit;
    }

    public function setOffset(int $offset) : void
    {
        $this->offset = $offset;
        if ($this->page) {
            $this->page = 0;
            $this->limit = 0;
        }
    }

    public function getOffset() : int
    {
        return $this->offset;
    }

    public function setPage(int $page) : void
    {
        $this->page = $page;
        $this->setPagingLimitOffset();
    }

    public function getPage() : int
    {
        return $this->page;
    }

    public function setPerPage(int $perPage) : void
    {
        $this->perPage = $perPage;
        if ($this->page) {
            $this->setPagingLimitOffset();
        }
    }

    public function getPerPage() : int
    {
        return $this->perPage;
    }

    protected function setPagingLimitOffset() : void
    {
        $this->limit = 0;
        $this->offset = 0;
        if ($this->page) {
            $this->limit = $this->perPage;
            $this->offset = $this->perPage * ($this->page - 1);
        }
    }

    public function buildEarly() : string
    {
        return '';
    }

    public function build() : string
    {
        $clause = '';

        if ($this->limit != 0) {
            $clause .= "LIMIT {$this->limit}";
        }

        if ($this->offset != 0) {
            $clause .= " OFFSET {$this->offset}";
        }

        if ($clause != '') {
            $clause = PHP_EOL . ltrim($clause);
        }

        return $clause;
    }
}
