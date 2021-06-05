<?php
namespace Atlas\Query;

use PDO;
use ReflectionClass;

abstract class QueryTest extends \PHPUnit\Framework\TestCase
{
    protected $query;
    protected $connection;
    protected $driver;

    protected function setUp() : void
    {
        parent::setUp();

        $rc = new ReflectionClass(Bind::CLASS);
        $rp = $rc->getProperty('instanceCount');
        $rp->setAccessible(true);
        $rp->setValue(0);

        $this->connection = new FakeConnection('fake');
        $this->driver = new Driver\FakeDriver();

        $this->query = $this->newQuery();
    }

    public function testStaticNew()
    {
        $class = substr(static::CLASS, 0, -4);

        $actual = $class::new('sqlite::memory:');
        $this->assertInstanceOf($class, $actual);

        $actual = $class::new(new FakeConnection('fake'));
        $this->assertInstanceOf($class, $actual);
    }

    protected function newQuery()
    {
        $class = substr(get_class($this), 0, -4);
        return new $class(
            $this->connection,
            $this->driver
        );
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
        $actual = $this->query->getBindValues();
        $this->assertSame([], $actual);

        $this->query->bindValues(['foo' => 'bar', 'baz' => 'dib']);
        $expect = [
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
        ];
        $actual = $this->query->getBindValues();
        $this->assertSame($expect, $actual);

        $this->query->bindValues(['zim' => 'gir']);
        $expect = [
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
            'zim' => ['gir', PDO::PARAM_STR],
        ];
        $actual = $this->query->getBindValues();
        $this->assertSame($expect, $actual);
    }
}
