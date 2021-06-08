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

use Atlas\Query\Statement\SelectStatement;
use BadMethodCallException;
use Generator;

/**
 * @method array|false fetchAll()
 * @method int fetchAffected()
 * @method array|false fetchColumn(int $column = 0)
 * @method array|false fetchGroup(int $style = PDO::FETCH_COLUMN)
 * @method array|false fetchKeyPair()
 * @method object|false fetchObject(string $class = 'stdClass', array $args = [])
 * @method array|false fetchObjects(string $class = 'stdClass', array $args = [])
 * @method array|false fetchOne()
 * @method array|false fetchUnique()
 * @method mixed fetchValue(int $column = 0)
 * @method Generator yieldAll()
 * @method Generator yieldColumn(int $column = 0)
 * @method Generator yieldKeyPair()
 * @method Generator yieldObjects(string $class = 'stdClass', array $args = [])
 * @method Generator yieldUnique()
 */
class Select extends SelectStatement
{
    use Query;

    public function __call(string $method, array $params) : mixed
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
}
