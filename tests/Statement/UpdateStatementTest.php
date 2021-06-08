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

class UpdateStatementTest extends StatementTest
{
    public function testCommon()
    {
        $this->statement->table('t1')
                    ->columns(['c1', 'c2', 'c3' => 'c3_value'])
                    ->set('c4', null)
                    ->set('c5', 'NOW()')
                    ->where('foo = :foo')
                    ->where('baz = :baz')
                    ->orWhere('zim = gir')
                    ->bindValues([
                        'foo' => 'bar',
                        'baz' => 'dib',
                    ]);

        $actual = $this->statement->getStatement();
        $expect = "
            UPDATE t1
            SET
                <<c1>> = :c1,
                <<c2>> = :c2,
                <<c3>> = :c3,
                <<c4>> = NULL,
                <<c5>> = NOW()
            WHERE
                foo = :foo
                AND baz = :baz
                OR zim = gir
        ";

        $this->assertSameSql($expect, $actual);

        $actual = $this->statement->getBindValues();
        $expect = array(
            'c3' => ['c3_value', PDO::PARAM_STR],
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
        );
        $this->assertSame($expect, $actual);

        // add RETURNING
        $this->statement->returning('c1', 'c2')
                    ->returning('c3');
        $actual = $this->statement->getStatement();
        $expect = "
            UPDATE t1
            SET
                <<c1>> = :c1,
                <<c2>> = :c2,
                <<c3>> = :c3,
                <<c4>> = NULL,
                <<c5>> = NOW()
            WHERE
                foo = :foo
                AND baz = :baz
                OR zim = gir
            RETURNING
                c1,
                c2,
                c3
        ";
        $this->assertSameSql($expect, $actual);
    }

    public function testHasColumns()
    {
        $this->statement->table('t1');
        $this->assertFalse($this->statement->hasColumns());
        $this->statement->columns(['c1', 'c2']);
        $this->assertTrue($this->statement->hasColumns());
    }
}
