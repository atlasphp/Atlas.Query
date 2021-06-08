<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query\Statement;

use PDO;
use ReflectionClass;
use Atlas\Query\Driver\FakeDriver;

abstract class StatementTest extends \PHPUnit\Framework\TestCase
{
    protected $statement;

    protected function setUp() : void
    {
        parent::setUp();

        $rc = new ReflectionClass(Bind::CLASS);
        $rp = $rc->getProperty('instanceCount');
        $rp->setAccessible(true);
        $rp->setValue(0);

        $this->statement = $this->newStatement();
    }

    public function testStaticNew()
    {
        $class = substr(static::CLASS, 0, -4);
        $actual = $class::new('sqlite');
        $this->assertInstanceOf($class, $actual);
    }

    protected function newStatement()
    {
        $class = substr(get_class($this), 0, -4);
        return new $class(new FakeDriver());
    }

    protected function assertSameSql($expect, $actual)
    {
        // remove leading and trailing whitespace per block and line
        $expect = trim($expect);
        $expect = preg_replace('/^[ \t]*/m', '', $expect);
        $expect = preg_replace('/[ \t]*$/m', '', $expect);

        // remove leading and trailing whitespace per block and line
        $actual = trim($actual);
        $actual = preg_replace('/^[ \t]*/m', '', $actual);
        $actual = preg_replace('/[ \t]*$/m', '', $actual);

        // normalize line endings to be sure tests will pass on windows and mac
        $expect = preg_replace('/\r\n|\n|\r/', PHP_EOL, $expect);
        $actual = preg_replace('/\r\n|\n|\r/', PHP_EOL, $actual);

        // are they the same now?
        $this->assertSame($expect, $actual);
    }

    public function testBindValues()
    {
        $actual = $this->statement->getBindValues();
        $this->assertSame([], $actual);

        $this->statement->bindValues(['foo' => 'bar', 'baz' => 'dib']);
        $expect = [
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
        ];
        $actual = $this->statement->getBindValues();
        $this->assertSame($expect, $actual);

        $this->statement->bindValues(['zim' => 'gir']);
        $expect = [
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
            'zim' => ['gir', PDO::PARAM_STR],
        ];
        $actual = $this->statement->getBindValues();
        $this->assertSame($expect, $actual);
    }
}
