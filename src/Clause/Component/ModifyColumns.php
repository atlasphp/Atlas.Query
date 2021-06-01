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

use Atlas\Query\Bind;
use Atlas\Query\Quoter\Quoter;

abstract class ModifyColumns extends Component
{
    protected array $list = [];

    public function __construct(protected Bind $bind, protected Quoter $quoter)
    {
    }

    public function hasAny() : bool
    {
        return ! empty($this->list);
    }

    public function hold(string $column, mixed ...$value) : void
    {
        $this->list[$column] = ":{$column}";

        if (! empty($value)) {
            $this->bind->value($column, ...$value);
        }
    }

    public function raw(string $column, mixed $value) : void
    {
        if ($value === null) {
            $value = 'NULL';
        }

        $this->list[$column] = $value;
        $this->bind->remove($column);
    }
}
