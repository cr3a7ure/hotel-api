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
use PHPSQLParser\PHPSQLParser;

/**
 * Tests the id type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\Id
 */
class IdTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function create() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM products WHERE id=1');

        $this->assertSame('1', Id::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createWithEmptyId() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM products WHERE name="test"');

        $this->assertSame('', Id::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function alias() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM products WHERE id=1');

        $this->assertSame('id', Id::alias($tokens));
    }
}