<?php
declare(strict_types=1);

/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query\Clause\Component;

use Atlas\Query\Bind;

class Conditions extends Component
{
    protected $bind;

    protected $type;

    protected $list = [];

    public function __construct(Bind $bind, string $type)
    {
        $this->bind = $bind;
        $this->type = $type;
    }

    public function and(string $expr, ...$inline) : void
    {
        $this->append('AND ', $expr, $inline);
    }

    public function or(string $expr, ...$inline) : void
    {
        $this->append('OR ', $expr, $inline);
    }

    public function cat(string $expr, ...$inline) : void
    {
        if (! empty($inline)) {
            $expr .= $this->bind->inline(...$inline);
        }

        if (empty($this->list)) {
            $this->list[] = '';
        }

        end($this->list);
        $key = key($this->list);

        $this->list[$key] .= $expr;
    }

    protected function append(string $andor, string $expr, array $inline) : void
    {
        if (! empty($inline)) {
            $expr .= $this->bind->inline(...$inline);
        }

        if (empty($this->list)) {
            $andor = '';
        }

        $this->list[] = $andor . $expr;
    }

    public function build() : string
    {
        if (empty($this->list)) {
            return '';
        }

        return PHP_EOL . $this->type . $this->indent($this->list);
    }
}
