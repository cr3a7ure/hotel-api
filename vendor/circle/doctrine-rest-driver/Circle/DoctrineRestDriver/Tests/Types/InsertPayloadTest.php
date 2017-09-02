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

use Circle\DoctrineRestDriver\Types\Id;
use Circle\DoctrineRestDriver\Types\InsertPayload;
use PHPSQLParser\PHPSQLParser;

/**
 * Tests the insert payload type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\InsertPayload
 */
class InsertPayloadTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createWithRawValues() {
        $parser   = new PHPSQLParser();
        $tokens   = $parser->parse('INSERT INTO products (name, value) VALUES (testname, testvalue)');
        $expected = json_encode([
            'name'  => 'testname',
            'value' => 'testvalue',
        ]);

        $this->assertSame($expected, InsertPayload::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createWithQuotedValues() {
        $parser   = new PHPSQLParser();
        $tokens   = $parser->parse('INSERT INTO products (name, value) VALUES ("testname", "testvalue")');
        $expected = json_encode([
            'name'  => 'testname',
            'value' => 'testvalue',
        ]);

        $this->assertSame($expected, InsertPayload::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createWithIntValue() {
        $parser   = new PHPSQLParser();
        $tokens   = $parser->parse('INSERT INTO products (name, value) VALUES (testname, 1)');
        $expected = json_encode([
            'name'  => 'testname',
            'value' => 1,
        ]);

        $this->assertSame($expected, InsertPayload::create($tokens));
    }
}