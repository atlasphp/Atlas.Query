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

class InsertStatementTest extends StatementTest
{
    public function testCommon()
    {
        $this->statement->into('t1')
                    ->columns(['c1', 'c2', 'c3' => 'c3_value'])
                    ->set('c4', 'NOW()')
                    ->set('c5', null)
                    ->columns(['cx' => 'cx_value'])
                    ->returning('c1', 'c2')
                    ->returning('c3');

        $actual = $this->statement->getStatement();
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

        $actual = $this->statement->getBindValues();
        $expect = [
            'c3' => ['c3_value', PDO::PARAM_STR],
            'cx' => ['cx_value', PDO::PARAM_STR],
        ];
        $this->assertSame($expect, $actual);
    }
}
