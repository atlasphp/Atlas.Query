<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

use Atlas\Query\QueryTest;
use PDO;

class DeleteTest extends QueryTest
{
    public function testCommon()
    {
        $this->query->from('t1')
                    ->where('foo = :foo')
                    ->where('baz = :baz')
                    ->orWhere('zim = gir')
                    ->returning('foo', 'baz', 'zim')
                    ->bindValues([
                        'foo' => 'bar',
                        'baz' => 'dib',
                    ]);

        $actual = $this->query->getStatement();
        $expect = "
            DELETE FROM t1
            WHERE
                foo = :foo
                AND baz = :baz
                OR zim = gir
            RETURNING
                foo,
                baz,
                zim
        ";

        $this->assertSameSql($expect, $actual);

        $actual = $this->query->getBindValues();
        $expect = array(
            'foo' => ['bar', PDO::PARAM_STR],
            'baz' => ['dib', PDO::PARAM_STR],
        );
        $this->assertSame($expect, $actual);
    }
}
