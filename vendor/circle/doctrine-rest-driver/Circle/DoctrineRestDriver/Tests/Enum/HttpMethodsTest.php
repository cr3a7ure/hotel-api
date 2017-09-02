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

namespace Circle\DoctrineRestDriver\Tests\Enums;

use Circle\DoctrineRestDriver\Enums\HttpMethods;
use Circle\DoctrineRestDriver\Enums\SqlOperations;

/**
 * Tests the http methods enum
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Enums\HttpMethods
 */
class HttpMethodsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::ofSqlOperation
     * @expectedException \Exception
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function ofSqlOperation() {
        $this->assertEquals(HttpMethods::GET, HttpMethods::ofSqlOperation(SqlOperations::SELECT));
        $this->assertEquals(HttpMethods::PUT, HttpMethods::ofSqlOperation(SqlOperations::UPDATE));
        $this->assertEquals(HttpMethods::DELETE, HttpMethods::ofSqlOperation(SqlOperations::DELETE));
        $this->assertEquals(HttpMethods::POST, HttpMethods::ofSqlOperation(SqlOperations::INSERT));

        HttpMethods::ofSqlOperation('invalid');
    }
}