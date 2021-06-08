<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Atlas\Query;

class InsertTest extends QueryTest
{
    public function testGetLastInsertId()
    {
        $query = $this->newQuery();
        $actual = $query->getLastInsertId('foo');
        $this->assertSame('foo-1', $actual);
    }
}
