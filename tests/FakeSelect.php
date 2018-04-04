<?php
declare(strict_types=1);

namespace Atlas\Query;

class FakeSelect extends Select
{
    public function __get($key)
    {
        return $this->$key;
    }
}
