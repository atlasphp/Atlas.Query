<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

use PDOStatement;
use Atlas\Query\Driver\FakeDriver;

abstract class QueryTest extends \PHPUnit\Framework\TestCase
{
    protected function getQueryClass()
    {
        return substr(get_class($this), 0, -4);
    }

    protected function newQuery(string $arg = null)
    {
        if ($arg === null) {
            $arg = new FakeConnection('fake');
        }

        $class = $this->getQueryClass();
        return $class::new($arg);
    }

    public function testNew()
    {
        $this->assertInstanceOf(
            $this->getQueryClass(),
            $this->newQuery()
        );

        $this->assertInstanceOf(
            $this->getQueryClass(),
            $this->newQuery('sqlite::memory:')
        );
    }

    public function testPerform()
    {
        $query = $this->newQuery();
        $this->assertInstanceOf(PDOStatement::CLASS, $query->perform());
    }
}
