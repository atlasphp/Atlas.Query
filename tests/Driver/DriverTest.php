<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Driver;

class DriverTest extends \PHPUnit\Framework\TestCase
{
    public function testMysql()
    {
        $driver = new MysqlDriver();
        $this->assertSame('`foo`', $driver->quoteIdentifier('foo'));
    }

    public function testPgsql()
    {
        $driver = new PgsqlDriver();
        $this->assertSame('"foo"', $driver->quoteIdentifier('foo'));
    }

    public function testSqlite()
    {
        $driver = new SqliteDriver();
        $this->assertSame('"foo"', $driver->quoteIdentifier('foo'));
    }

    public function testSqlsrv()
    {
        $driver = new SqlsrvDriver();
        $this->assertSame('[foo]', $driver->quoteIdentifier('foo'));
    }
}
