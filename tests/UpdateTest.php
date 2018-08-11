<?php
namespace Atlas\Query;

use Atlas\Query\QueryTest;
use PDO;

class UpdateTest extends QueryTest
{
    public function testCommon()
    {
        $this->query->table('t1')
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

        $actual = $this->query->getStatement();
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

        $actual = $this->query->getBindValues();
        $expect = array(
            'c3' => ['c3_value', PDO::PARAM_STR],
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
        );
        $this->assertSame($expect, $actual);

        // add RETURNING
        $this->query->returning('c1', 'c2')
                    ->returning('c3');
        $actual = $this->query->getStatement();
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
        $this->query->table('t1');
        $this->assertFalse($this->query->hasColumns());
        $this->query->columns(['c1', 'c2']);
        $this->assertTrue($this->query->hasColumns());
    }
}
