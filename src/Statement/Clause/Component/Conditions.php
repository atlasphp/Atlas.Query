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

use Atlas\Query\Statement\Bind;

class Conditions extends Component
{
    protected array $list = [];

    public function __construct(protected Bind $bind, protected string $type)
    {
    }

    public function and(string $expr, mixed ...$bindInline) : void
    {
        $this->append('AND ', $expr, $bindInline);
    }

    public function andSprintf(string $format, mixed ...$bindInline) : void
    {
        $this->and($this->bind->sprintf($format, ...$bindInline));
    }

    public function or(string $expr, mixed ...$bindInline) : void
    {
        $this->append('OR ', $expr, $bindInline);
    }

    public function orSprintf(string $format, mixed ...$bindInline) : void
    {
        $this->or($this->bind->sprintf($format, ...$bindInline));
    }

    public function cat(string $expr, mixed ...$bindInline) : void
    {
        if (! empty($bindInline)) {
            $expr .= $this->bind->inline(...$bindInline);
        }

        if (empty($this->list)) {
            $this->list[] = '';
        }

        end($this->list);
        $key = key($this->list);
        $this->list[$key] .= $expr;
    }

    public function catSprintf(string $format, mixed ...$bindInline) : void
    {
        $this->cat($this->bind->sprintf($format, ...$bindInline));
    }

    protected function append(
        string $andor,
        string $expr,
        array $bindInline
    ) : void
    {
        if (! empty($bindInline)) {
            $expr .= $this->bind->inline(...$bindInline);
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
