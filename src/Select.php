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

use BadMethodCallException;
use Generator;

/**
 * @method array fetchAll(string $statement, array $values = [])
 * @method int fetchAffected(string $statement, array $values = [])
 * @method array fetchColumn(string $statement, array $values = [], int $column = 0)
 * @method array fetchGroup(string $statement, array $values = [], int $style = PDO::FETCH_COLUMN)
 * @method array fetchKeyPair(string $statement, array $values = [])
 * @method mixed fetchObject(string $statement, array $values = [], string $class = 'stdClass', array $args = [])
 * @method array fetchObjects(string $statement, array $values = [], string $class = 'stdClass', array $args = [])
 * @method array|null fetchOne(string $statement, array $values = [])
 * @method array fetchUnique(string $statement, array $values = [])
 * @method mixed fetchValue(string $statement, array $values = [], int $column = 0)
 * @method Generator yieldAll(string $statement, array $values = [])
 * @method Generator yieldColumn(string $statement, array $values = [], int $column = 0)
 * @method Generator yieldKeyPair(string $statement, array $values = [])
 * @method Generator yieldObjects(string $statement, array $values = [], string $class = 'stdClass', array $args = [])
 * @method Generator yieldUnique(string $statement, array $values = [])
 */
class Select extends Query
{
    use Clause\SelectColumns;
    use Clause\Where;
    use Clause\GroupBy;
    use Clause\Having;
    use Clause\OrderBy;
    use Clause\Limit;

    protected $as;
    protected $from;
    protected $unions = [];
    protected $forUpdate = false;

    public function __clone()
    {
        $vars = get_object_vars($this);
        unset($vars['bind']);
        foreach ($vars as $name => $prop) {
            if (is_object($prop)) {
                $this->$name = clone $prop;
            }
        }
    }

    public function __call($method, $params)
    {
        $prefix = substr($method, 0, 5);
        if ($prefix == 'fetch' || $prefix == 'yield') {
            return $this->connection->$method(
                $this->getStatement(),
                $this->getBindValues(),
                ...$params
            );
        }

        throw new BadMethodCallException($method);
    }

    public function forUpdate(bool $enable = true)
    {
        $this->forUpdate = $enable;
        return $this;
    }

    public function distinct(bool $enable = true)
    {
        $this->flags->set('DISTINCT', $enable);
        return $this;
    }

    public function from(string $ref)
    {
        $this->from->table($ref);
        return $this;
    }

    public function join(string $join, string $ref, string $condition = '', ...$inline)
    {
        $join = strtoupper(trim($join));
        if (substr($join, -4) != 'JOIN') {
            $join .= ' JOIN';
        }
        $this->from->join($join, $ref, $condition, ...$inline);
        return $this;
    }

    public function catJoin(string $expr, ...$inline)
    {
        $this->from->catJoin($expr, ...$inline);
        return $this;
    }

    public function union()
    {
        $this->unions[] = $this->getStatement() . PHP_EOL . 'UNION' . PHP_EOL;
        $this->reset();
        return $this;
    }

    public function unionAll()
    {
        $this->unions[] = $this->getStatement() . PHP_EOL . 'UNION ALL' . PHP_EOL;
        $this->reset();
        return $this;
    }

    public function as(string $as)
    {
        $this->as = $as;
        return $this;
    }

    public function resetFrom()
    {
        $this->from = new Clause\Component\From($this->bind);
        return $this;
    }

    public function resetAs()
    {
        $this->as = null;
        return $this;
    }

    public function subSelect()
    {
        return new Select($this->connection, $this->bind);
    }

    public function getStatement() : string
    {
        $stm = implode('', $this->unions)
            . 'SELECT'
            . $this->flags->build()
            . $this->limit->buildEarly()
            . $this->columns->build()
            . $this->from->build()
            . $this->where->build()
            . $this->groupBy->build()
            . $this->having->build()
            . $this->orderBy->build()
            . $this->limit->build()
            . ($this->forUpdate ? PHP_EOL . 'FOR UPDATE' : '');

        if ($this->as === null) {
            return $stm;
        }

        return "(" . PHP_EOL . $stm . PHP_EOL . ") AS {$this->as}";
    }
}
