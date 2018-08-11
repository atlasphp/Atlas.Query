<?php
namespace Atlas\Query;

use Atlas\Query\QueryTest;
use PDO;

class InsertTest extends QueryTest
{
    public function testCommon()
    {
        $this->query->into('t1')
                    ->columns(['c1', 'c2', 'c3' => 'c3_value'])
                    ->set('c4', 'NOW()')
                    ->set('c5', null)
                    ->columns(['cx' => 'cx_value'])
                    ->returning('c1', 'c2')
                    ->returning('c3');

        $actual = $this->query->getStatement();
        $expect = '
            INSERT INTO t1 (
                <<c1>>,
                <<c2>>,
                <<c3>>,
                <<c4>>,
                <<c5>>,
                <<cx>>
            ) VALUES (
                :c1,
                :c2,
                :c3,
                NOW(),
                NULL,
                :cx
            )
            RETURNING
                c1,
                c2,
                c3
        ';

        $this->assertSameSql($expect, $actual);

        $actual = $this->query->getBindValues();
        $expect = [
            'c3' => ['c3_value', PDO::PARAM_STR],
            'cx' => ['cx_value', PDO::PARAM_STR],
        ];
        $this->assertSame($expect, $actual);
    }

    public function testGetLastInsertId()
    {
        $actual = $this->query->getLastInsertId('foo');
        $this->assertSame('foo-1', $actual);
    }
}
