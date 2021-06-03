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

use PDO;

class Bind
{
    static protected int $instanceCount = 0;

    protected int $inlineCount = 0;

    protected int $inlinePrefix = 0;

    protected array $values = [];

    public function __construct()
    {
        $this->incrementInstanceCount();
    }

    public function __clone()
    {
        $this->incrementInstanceCount();
    }

    protected function incrementInstanceCount() : void
    {
        static::$instanceCount ++;
        $this->inlinePrefix = static::$instanceCount;
    }

    public function reset() : void
    {
        $this->inlineCount = 0;
        $this->values = [];
    }

    public function merge(array $values) : void
    {
        $this->values += $values;
    }

    public function value(string $key, mixed $value, int $type = -1) : void
    {
        if ($type === -1) {
            $type = $this->getType($value);
        }

        $this->values[$key] = [$value, $type];
    }

    protected function getType(mixed $value) : int
    {
        if (is_null($value)) {
            return PDO::PARAM_NULL;
        }

        if (is_bool($value)) {
            return PDO::PARAM_BOOL;
        }

        if (is_int($value)) {
            return PDO::PARAM_INT;
        }

        return PDO::PARAM_STR;
    }

    public function values(array $values, int $type = -1) : void
    {
        foreach ($values as $key => $value) {
            $this->value($key, $value, $type);
        }
    }

    public function getArrayCopy() : array
    {
        return $this->values;
    }

    public function remove(string $key) : void
    {
        unset($this->values[$key]);
    }

    public function inline(mixed $value, int $type = -1) : string
    {
        if ($value instanceof Query) {
            $this->values += $value->getBindValues();
            return '(' . $value->getStatement() . ')';
        }

        if (is_array($value)) {
            return $this->inlineArray($value, $type);
        }

        $key = $this->inlineValue($value, $type);
        return ":{$key}";
    }

    protected function inlineArray(array $array, int $type) : string
    {
        $keys = [];

        foreach ($array as $value) {
            $key = $this->inlineValue($value, $type);
            $keys[] = ":{$key}";
        }

        return '(' . implode(', ', $keys) . ')';
    }

    protected function inlineValue(mixed $value, int $type) : string
    {
        $this->inlineCount ++;
        $key = "_{$this->inlinePrefix}_{$this->inlineCount}_";
        $this->value($key, $value, $type);
        return $key;
    }

    public function sprintf(string $format, mixed ...$values) : string
    {
        $tokens = [];

        foreach ($values as $value) {
            $tokens[] = $this->inline($value);
        }

        return sprintf($format, ...$tokens);
    }
}
