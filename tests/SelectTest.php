<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

use BadMethodCallException;
use Generator;

class SelectTest extends QueryTest
{
    public function test__call()
    {
        $query = $this->newQuery();
        $this->assertSame([], $query->fetchAll());
        $this->assertInstanceOf(Generator::CLASS, $query->yieldAll());

        $this->expectException(BadMethodCallException::CLASS);
        $query->noSuchMethod();
    }
}
