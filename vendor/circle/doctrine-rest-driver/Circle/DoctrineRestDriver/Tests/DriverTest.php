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

use Circle\DoctrineRestDriver\Driver;

/**
 * Tests the driver
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Driver
 */
class DriverTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Driver
     */
    private $driver;

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        $this->driver = new Driver();
    }

    /**
     * @test
     * @group  unit
     * @covers ::getDatabasePlatform
     */
    public function getDatabasePlatform() {
        $this->assertInstanceOf('Doctrine\DBAL\Platforms\MySqlPlatform', $this->driver->getDatabasePlatform());
    }

    /**
     * @test
     * @group  unit
     * @covers ::getSchemaManager
     */
    public function getSchemaManager() {
        $connection = $this->getMockBuilder('Circle\DoctrineRestDriver\Connection')->disableOriginalConstructor()->getMock();
        $this->assertInstanceOf('Doctrine\DBAL\Schema\MySqlSchemaManager', $this->driver->getSchemaManager($connection));
    }

    /**
     * @test
     * @group  unit
     * @covers ::getName
     */
    public function getNameTest() {
        $this->assertSame('circle_rest', $this->driver->getName());
    }

    /**
     * @test
     * @group  unit
     * @covers ::getDatabase
     */
    public function getDatabase() {
        $connection = $this->getMockBuilder('Circle\DoctrineRestDriver\Connection')->disableOriginalConstructor()->getMock();
        $this->assertSame('rest_database', $this->driver->getDatabase($connection));
    }

    /**
     * @test
     * @group  unit
     * @covers ::connect
     * @covers ::<private>
     */
    public function connect() {
        $params = [
            'driverOptions' => [
                'security_strategy' => 'none'
            ],
            'user'     => 'user',
            'password' => 'password',
            'host'     => 'localhost'
        ];
        $connection = $this->driver->connect($params);
        $this->assertInstanceOf('Circle\DoctrineRestDriver\Connection', $connection);
    }
}