<?php
/**
 * This file is part of DoctrineRestDriver.
 *
 * DoctrineRestDriver is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DoctrineRestDriver is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DoctrineRestDriver.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Circle\DoctrineRestDriver\Tests;

use Circle\DoctrineRestDriver\Statement;

/**
 * Tests the statement
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Statement
 */
class StatementTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Statement
     */
    private $statement;

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        $params = [
            'host'          => 'http://www.circle.ai',
            'user'          => 'Aladdin',
            'password'      => 'OpenSesame',
            'driverOptions' => [
                'authenticator_class' => 'HttpAuthentication'
            ]
        ];
        $this->statement = new Statement('SELECT name FROM product WHERE id=1', $params);
    }

    /**
     * @test
     * @group unit
     * @expectedException \Exception
     * @covers ::bindParam
     */
    public function bindParam() {
        $test = 'test';
        $this->statement->bindParam('test', $test);
    }

    /**
     * @test
     * @group unit
     * @covers ::errorInfo
     */
    public function errorInfo() {
        $this->assertSame(null, $this->statement->errorInfo());
    }

    /**
     * @test
     * @group unit
     * @covers ::errorCode
     */
    public function errorCode() {
        $this->assertSame(null, $this->statement->errorCode());
    }

    /**
     * @test
     * @group unit
     * @covers ::columnCount
     */
    public function columnCount() {
        $this->assertSame(0, $this->statement->columnCount());
    }

    /**
     * @test
     * @group unit
     * @expectedException \Exception
     * @covers ::fetchColumn
     */
    public function fetchColumn() {
        $this->statement->fetchColumn(1);
    }

    /**
     * @test
     * @group unit
     * @covers ::getIterator
     */
    public function getIterator() {
        $this->assertSame('SELECT name FROM product WHERE id=1', $this->statement->getIterator());
    }

    /**
     * @test
     * @group unit
     * @expectedException \Exception
     * @covers ::fetchAll
     */
    public function fetchAllFalseMode() {
        $this->statement->fetchAll(\PDO::FETCH_CLASS);
    }

    /**
     * @test
     * @group unit
     * @covers ::fetchAll
     */
    public function fetchAll() {
        $this->assertEquals([], $this->statement->fetchAll(\PDO::FETCH_ASSOC));
    }
}