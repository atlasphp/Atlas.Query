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
    static protected $inlineCount = 0;

    protected $values = [];

    public function merge(array $values) : void
    {
        $this->values += $values;
    }

    public function value(string $key, $value, int $type = -1) : void
    {
        if ($type === -1) {
            $type = $this->getType($value);
        }
        $this->values[$key] = [$value, $type];
    }

    protected function getType($value)
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

    public function remove(string $key)
    {
        unset($this->values[$key]);
    }

    public function inline($value, int $type = -1) : string
    {
        if ($value instanceof Select) {
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

    protected function inlineValue($value, $type) : string
    {
        static::$inlineCount ++;
        $key = '__' . static::$inlineCount . '__';
        $this->value($key, $value, $type);

        return $key;
    }

    public function sprintf(string $format, ...$values) : string
    {
        $tokens = [];

        foreach ($values as $value) {
            $tokens[] = $this->inline($value);
        }

        return sprintf($format, ...$tokens);
    }
}
