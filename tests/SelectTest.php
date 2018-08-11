<?php
namespace Atlas\Query;

use Atlas\Query\QueryTest;
use Generator;
use PDO;
use PDOStatement;

class SelectTest extends QueryTest
{
    public function test__call()
    {
        $this->assertSame([], $this->query->fetchAll());
        $this->assertInstanceOf(Generator::CLASS, $this->query->yieldAll());
        $this->assertInstanceOf(PDOStatement::CLASS, $this->query->perform());

        $this->expectException('BadMethodCallException');
        $this->query->exec();
    }

    public function testDistinct()
    {
        $this->query->distinct()
                     ->from('t1')
                     ->columns('t1.c1', 't1.c2', 't1.c3');

        $actual = $this->query->getStatement();

        $expect = '
            SELECT DISTINCT
                t1.c1,
                t1.c2,
                t1.c3
            FROM
                t1
        ';
        $this->assertSameSql($expect, $actual);
    }

    public function testDuplicateFlag()
    {
        $this->query->distinct()
                    ->distinct()
                    ->from('t1')
                    ->columns('t1.c1', 't1.c2', 't1.c3');

        $actual = $this->query->getStatement();

        $expect = '
            SELECT DISTINCT
                t1.c1,
                t1.c2,
                t1.c3
            FROM
                t1
        ';
        $this->assertSameSql($expect, $actual);
    }

    public function testFlagUnset()
    {
        $this->query->distinct()
                    ->distinct(false)
                    ->from('t1')
                    ->columns('t1.c1', 't1.c2', 't1.c3');

        $actual = $this->query->getStatement();

        $expect = '
            SELECT
                t1.c1,
                t1.c2,
                t1.c3
            FROM
                t1
        ';
        $this->assertSameSql($expect, $actual);
    }

    public function testColumns()
    {
        $this->assertFalse($this->query->hasColumns());

        $this->query->columns(
            't1.c1',
            'c2 AS a2',
            'COUNT(t1.c3)'
        );

        $this->assertTrue($this->query->hasColumns());

        $actual = $this->query->getStatement();
        $expect = '
            SELECT
                t1.c1,
                c2 AS a2,
                COUNT(t1.c3)
        ';
        $this->assertSameSql($expect, $actual);
    }

    public function testFrom()
    {
        $this->query->columns('*');
        $this->query->from('t1')
                    ->from('t2');

        $actual = $this->query->getStatement();
        $expect = '
            SELECT
                *
            FROM
                t1,
                t2
        ';
        $this->assertSameSql($expect, $actual);
    }

    public function testFromSubSelect()
    {
        $this->query
            ->columns('*')
            ->from($this->query->subSelect()
                ->columns('*')
                ->from('t2')
                ->as('a2')
                ->getStatement()
            );

        $expect = '
            SELECT
                *
            FROM
                (
                    SELECT
                        *
                    FROM
                        t2
                ) AS a2
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testFromSubSelectObject()
    {
        // note that these are "out of order" on purpose,
        // to make sure that sequential binding happens correctly.
        $this->query->columns('*')
            ->where('a2.baz = ', 'dib')
            ->from($this->query->subSelect()
                ->columns('*')
                ->from('t2')
                ->where('foo = ', 'bar')
                ->as('a2')
                ->getStatement()
            );

        $expect = '
            SELECT
                *
            FROM
                (
                    SELECT
                        *
                    FROM
                        t2
                    WHERE
                        foo = :__2__
                ) AS a2
            WHERE
                a2.baz = :__1__
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $expect = [
            '__1__' => ['dib', PDO::PARAM_STR],
            '__2__' => ['bar', PDO::PARAM_STR]
        ];

        $actual = $this->query->getBindValues();
        $this->assertSame($expect, $actual);
    }

    public function testJoin()
    {
        $this->query->columns('*');
        $this->query->from('t1');
        $this->query->join('left', 't2', 't1.id = t2.id');
        $this->query->join('inner', 't3 AS a3', 't2.id = a3.id');
        $this->query->from('t4');
        $this->query->join('natural', 't5');
        $expect = '
            SELECT
                *
            FROM
                t1
                    LEFT JOIN t2 ON t1.id = t2.id
                    INNER JOIN t3 AS a3 ON t2.id = a3.id,
                t4
                    NATURAL JOIN t5
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testJoinAndBind()
    {
        $this->query->columns('*');
        $this->query->from('t1');
        $this->query->join(
            'left',
            't2',
            't1.id = t2.id AND t1.foo = ',
            'bar'
        );
        $this->query->catJoin(' AND t1.baz = ', 'dib');

        $expect = '
            SELECT
                *
            FROM
                t1
            LEFT JOIN t2 ON t1.id = t2.id AND t1.foo = :__1__ AND t1.baz = :__2__
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $expect = [
            '__1__' => ['bar', PDO::PARAM_STR],
            '__2__' => ['dib', PDO::PARAM_STR]
        ];
        $actual = $this->query->getBindValues();
        $this->assertSame($expect, $actual);
    }

    public function testJoinSubSelect()
    {
        $sub1 = '(SELECT * FROM t2) AS a2';
        $sub2 = '(SELECT * FROM t3) AS a3';
        $this->query->columns('*');
        $this->query->from('t1');
        $this->query->join('left', $sub1, 't2.c1 = a3.c1');
        $this->query->join('natural', $sub2);
        $expect = '
            SELECT
                *
            FROM
                t1
                    LEFT JOIN (SELECT * FROM t2) AS a2 ON t2.c1 = a3.c1
                    NATURAL JOIN (SELECT * FROM t3) AS a3
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testJoinSubSelectObject()
    {
        $sub = $this->query->subSelect();
        $sub->columns('*')->from('t2')->where('foo = ', 'bar')->as('a3');

        $this->query->columns('*');
        $this->query->from('t1');
        $this->query->join('left', $sub->getStatement(), 't2.c1 = a3.c1');
        $this->query->where('baz = ', 'dib');

        $expect = '
            SELECT
                *
            FROM
                t1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            t2
                        WHERE
                            foo = :__1__
                    ) AS a3 ON t2.c1 = a3.c1
            WHERE
                baz = :__2__
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testJoinOrder()
    {
        $this->query
            ->columns('*')
            ->from('t1')
            ->join('inner', 't2', 't2.id = t1.id')
            ->join('left', 't3', 't3.id = t2.id')
            ->from('t4')
            ->join('inner', 't5', 't5.id = t4.id');

        $expect = '
            SELECT
                *
            FROM
                t1
                    INNER JOIN t2 ON t2.id = t1.id
                    LEFT JOIN t3 ON t3.id = t2.id,
                t4
                    INNER JOIN t5 ON t5.id = t4.id
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testJoinOnAndUsing()
    {
        $this->query
            ->columns('*')
            ->from('t1')
            ->join('inner', 't2', 'ON t2.id = t1.id')
            ->join('left', 't3', 'USING (id)');

        $expect = '
            SELECT
                *
            FROM
                t1
                    INNER JOIN t2 ON t2.id = t1.id
                    LEFT JOIN t3 USING (id)
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testWhere()
    {
        $this->query
            ->columns('*')
            ->where('c1 = c2')
            ->andWhere('c3 = :c3')
            ->andWhere('c4 IN', [null, true, 1])
            ->catWhere(' AND c5 = ' . $this->query->bindInline(2))
            ->bindValue('c3', 'foo');

        $expect = '
            SELECT
                *
            WHERE
                c1 = c2
                AND c3 = :c3
                AND c4 IN(:__1__, :__2__, :__3__) AND c5 = :__4__
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $actual = $this->query->getBindValues();
        $expect = [
            '__1__' => [null, PDO::PARAM_NULL],
            '__2__' => [true, PDO::PARAM_BOOL],
            '__3__' => [1, PDO::PARAM_INT],
            '__4__' => [2, PDO::PARAM_INT],
            'c3' => ['foo', PDO::PARAM_STR],
        ];
        $this->assertSame($expect, $actual);
    }

    public function testOrWhere()
    {
        $this->query
            ->columns('*')
            ->catWhere('c1 = ', 'bar')
            ->orWhere('c3 = :c3')
            ->bindValue('c3', 'foo');

        $expect = '
            SELECT
                *
            WHERE
                c1 = :__1__
                OR c3 = :c3
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $actual = $this->query->getBindValues();
        $expect = [
            '__1__' => ['bar', PDO::PARAM_STR],
            'c3' => ['foo', PDO::PARAM_STR]
        ];
        $this->assertSame($expect, $actual);
    }

    public function testWhereEquals()
    {
        $actual = $this->query
            ->columns('*')
            ->whereEquals([
                'foo' => [1, 2, 3],
                'bar' => null,
                'baz' => 'baz_value',
                'dib = NOW()',
            ]);

        $expect = '
            SELECT
                *
            WHERE
                foo IN (:__1__, :__2__, :__3__)
                AND bar IS NULL
                AND baz = :__4__
                AND dib = NOW()
        ';

        $this->assertSameSql($expect, $actual->getStatement());
    }

    public function testGroupBy()
    {
        $this->query
            ->columns('*')
            ->groupBy('c1')
            ->groupBy('t2.c2');

        $expect = '
            SELECT
                *
            GROUP BY
                c1,
                t2.c2
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testHaving()
    {
        $this->query
            ->columns('*')
            ->having('c1 = c2')
            ->andHaving('c3 = :c3')
            ->orHaving('(c4 = 1 ')
            ->catHaving('AND c5 = 2)')
            ->bindValue('c3', 'foo');

        $expect = '
            SELECT
                *
            HAVING
                c1 = c2
                AND c3 = :c3
                OR (c4 = 1 AND c5 = 2)
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $actual = $this->query->getBindValues();
        $expect = [
            'c3' => ['foo', PDO::PARAM_STR]
        ];
        $this->assertSame($expect, $actual);
    }

    public function testOrHaving()
    {
        $this->query
            ->columns('*')
            ->orHaving('c1 = c2')
            ->orHaving('c3 = :c3')
            ->bindValue('c3', 'foo');

        $expect = '
            SELECT
                *
            HAVING
                c1 = c2
                OR c3 = :c3
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $actual = $this->query->getBindValues();
        $expect = [
            'c3' => ['foo', PDO::PARAM_STR],
        ];
        $this->assertSame($expect, $actual);
    }

    public function testOrderBy()
    {
        $this->query
            ->columns('*')
            ->orderBy('c1', 'UPPER(t2.c2)');

        $expect = '
            SELECT
                *
            ORDER BY
                c1,
                UPPER(t2.c2)
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testLimitOffset()
    {
        $this->query->columns('*');
        $this->query->limit(10);
        $expect = '
            SELECT
                *
            LIMIT 10
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $this->query->offset(40);
        $expect = '
            SELECT
                *
            LIMIT 10 OFFSET 40
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testPage()
    {
        $this->query->columns('*');
        $this->query->page(5);
        $expect = '
            SELECT
                *
            LIMIT 10 OFFSET 40
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $this->query->perPage(25);
        $expect = '
            SELECT
                *
            LIMIT 25 OFFSET 100
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testForUpdate()
    {
        $this->query->columns('*');
        $this->query->forUpdate();
        $expect = '
            SELECT
                *
            FOR UPDATE
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testUnion()
    {
        $this->query->columns('c1')
                     ->from('t1')
                     ->union()
                     ->columns('c2')
                     ->from('t2');

        $expect = '
            SELECT
                c1
            FROM
                t1
            UNION
            SELECT
                c2
            FROM
                t2
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testUnionAll()
    {
        $this->query->columns('c1')
                     ->from('t1')
                     ->unionAll()
                     ->columns('c2')
                     ->from('t2');
        $expect = '
            SELECT
                c1
            FROM
                t1
            UNION ALL
            SELECT
                c2
            FROM
                t2
        ';

        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testWhereSubSelect()
    {
        $select = $this->newQuery();
        $select->columns('*')
            ->from('table2 AS t2')
            ->where('field IN ', $select->subSelect()
                ->columns('col1')
                ->from('table1 AS t1')
            );

        $expect = '
            SELECT
                *
            FROM
                table2 AS t2
            WHERE
                field IN (SELECT
                col1
            FROM
                table1 AS t1)
        ';
        $actual = $select->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testIssue49()
    {
        $limit = new \Atlas\Query\Clause\Component\Limit();

        $this->assertSame(0, $limit->getPage());
        $this->assertSame(10, $limit->getPerPage());
        $this->assertSame(0, $limit->getLimit());
        $this->assertSame(0, $limit->getOffset());

        $limit->setPage(3);
        $this->assertSame(3, $limit->getPage());
        $this->assertSame(10, $limit->getPerPage());
        $this->assertSame(10, $limit->getLimit());
        $this->assertSame(20, $limit->getOffset());

        $limit->setLimit(10);
        $this->assertSame(0, $limit->getPage());
        $this->assertSame(10, $limit->getPerPage());
        $this->assertSame(10, $limit->getLimit());
        $this->assertSame(0, $limit->getOffset());

        $limit->setPage(3);
        $limit->setPerPage(50);
        $this->assertSame(3, $limit->getPage());
        $this->assertSame(50, $limit->getPerPage());
        $this->assertSame(50, $limit->getLimit());
        $this->assertSame(100, $limit->getOffset());

        $limit->setOffset(10);
        $this->assertSame(0, $limit->getPage());
        $this->assertSame(50, $limit->getPerPage());
        $this->assertSame(0, $limit->getLimit());
        $this->assertSame(10, $limit->getOffset());
    }

    public function testAs()
    {
        $this->query->columns('*')->from('t1')->as('foo');
        $expect = '
            (
                SELECT
                    *
                FROM
                    t1
            ) AS foo
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function test__clone()
    {
        $query = new FakeSelect($this->connection, $this->queryFactory->newBind());
        $clone = clone $query;

        $this->assertSame($query->bind, $clone->bind);
        $vars = ['flags', 'columns', 'from', 'where', 'groupBy', 'having', 'orderBy', 'limit'];
        foreach ($vars as $var) {
            $this->assertNotSame($query->$var, $clone->$var);
        }
    }

    public function testUnionSelectCanHaveSameAliasesInDifferentSelects()
    {
        $select = $this->query
            ->columns(
                '...'
            )
            ->from('a')
            ->join('INNER', 'c', 'a_cid = c_id')
            ->union()
            ->columns(
                '...'
            )
            ->from('b')
            ->join('INNER', 'c', 'b_cid = c_id');

        $expected = 'SELECT
                    ...
                    FROM
                    a
                    INNER JOIN c ON a_cid = c_id
                    UNION
                    SELECT
                    ...
                    FROM
                    b
                    INNER JOIN c ON b_cid = c_id';

        $actual = (string) $select->getStatement();
        $this->assertSameSql($expected, $actual);
    }

    public function testQuoteIdentifier()
    {
        $actual = $this->query->quoteIdentifier('foo');
        $this->assertSame('<<foo>>', $actual);
    }

    public function testSetFlag()
    {
        $this->query->columns('*')->from('t1')->setFlag('LOW_PRIORITY');
        $expect = '
            SELECT LOW_PRIORITY
                *
            FROM
                t1
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testSqlsrvLimitOffset()
    {
        $this->connection = new FakeConnection('sqlsrv');
        $this->query = $this->newQuery();

        $this->query->columns('*');
        $this->query->limit(10);
        $expect = '
            SELECT TOP 10
                *
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);

        $this->query->offset(40);
        $expect = '
            SELECT
                *
            OFFSET 40 ROWS FETCH NEXT 10 ROWS ONLY
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }

    public function testSqlsrvPage()
    {
        $this->connection = new FakeConnection('sqlsrv');
        $this->query = $this->newQuery();

        $this->query->columns('*');
        $this->query->page(5);
        $expect = '
            SELECT
                *
            OFFSET 40 ROWS FETCH NEXT 10 ROWS ONLY
        ';
        $actual = $this->query->getStatement();
        $this->assertSameSql($expect, $actual);
    }
}
