<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Statement\Clause\Component;

use Atlas\Query\Driver\Driver;
use Atlas\Query\Statement\Bind;
use Atlas\Query\Statement\Statement;

class With extends Component
{
    protected array $ctes = [];

    protected bool $recursive = false;

    public function __construct(protected Bind $bind, protected Driver $driver)
    {
    }

    public function setCte(string $name, array $columns, mixed $query) : void
    {
        $this->ctes[$name] = [$columns, $query];
    }

    public function setRecursive(bool $recursive) : void
    {
        $this->recursive = $recursive;
    }

    public function build() : string
    {
        if (empty($this->ctes)) {
            return '';
        }

        $ctes = [];

        foreach ($this->ctes as $name => $info) {
            list($columns, $query) = $info;
            $ctes[] = $this->buildCte($name, $columns, $query);
        }

        return ($this->recursive ? 'WITH RECURSIVE' : 'WITH')
            . $this->indentCsv($ctes)
            . PHP_EOL;
    }

    protected function buildCte(string $name, array $columns, string|Statement $query) : string
    {
        $sql = $this->driver->quoteIdentifier($name);

        foreach ($columns as $key => $column) {
            $columns[$key] = $this->driver->quoteIdentifier($column);
        }

        if (! empty($columns)) {
            $sql .= ' (' . implode(', ', $columns) . ')';
        }

        if ($query instanceof Statement) {
            $this->bind->merge($query->getBindValues());
            $query = $query->getQueryString();
        }

        $sql .= " AS (" . PHP_EOL . "    {$query}" . PHP_EOL . ")";

        return $sql;
    }
}
