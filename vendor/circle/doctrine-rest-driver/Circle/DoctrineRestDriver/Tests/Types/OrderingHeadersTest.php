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

namespace Circle\DoctrineRestDriver\Tests\Types;

use Circle\DoctrineRestDriver\Types\OrderingHeaders;
use PHPSQLParser\PHPSQLParser;
/**
 * Tests ordering headers
 *
 * @author    Djane Rey Mabelin <thedjaney@gmail.com>
 * @copyright 2016
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\OrderingHeaders
 */
class OrderingHeadersTest extends \PHPUnit_Framework_TestCase {


    /**
     * @var array
     */
    private $expected;

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        $this->expected = [
            'Order: name ASC',
        ];
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function create() {
        $query  = 'SELECT name FROM products a ORDER BY name ASC';
        $parser = new PHPSQLParser();
        $token  = $parser->parse($query);
        $header = OrderingHeaders::create($token);
        $this->assertEquals($this->expected, $header);
    }
}
