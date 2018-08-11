<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Atlas\Query\Quoter;

class QuoterTest extends \PHPUnit\Framework\TestCase
{
    public function testMysql()
    {
        $quoter = new MysqlQuoter();
        $this->assertSame('`foo`', $quoter->quoteIdentifier('foo'));
    }

    public function testPgsql()
    {
        $quoter = new PgsqlQuoter();
        $this->assertSame('"foo"', $quoter->quoteIdentifier('foo'));
    }

    public function testSqlite()
    {
        $quoter = new SqliteQuoter();
        $this->assertSame('"foo"', $quoter->quoteIdentifier('foo'));
    }

    public function testSqlsrv()
    {
        $quoter = new SqlsrvQuoter();
        $this->assertSame('[foo]', $quoter->quoteIdentifier('foo'));
    }
}
