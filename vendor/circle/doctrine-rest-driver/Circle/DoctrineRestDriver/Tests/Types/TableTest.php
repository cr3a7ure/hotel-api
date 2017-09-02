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

use Circle\DoctrineRestDriver\Types\Table;
use PHPSQLParser\PHPSQLParser;

/**
 * Tests the table type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\Table
 *
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 */
class TableTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createSelect() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM products p0');

        $this->assertSame('products', Table::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function aliasSelect() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM products p0');

        $this->assertSame('p0', Table::alias($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createSelectWithUrl() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM "http://www.circle.ai/api" p0');

        $this->assertSame('http://www.circle.ai/api', Table::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function aliasSelectWithUrl() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('SELECT name FROM "http://www.circle.ai/api" p0');

        $this->assertSame('p0', Table::alias($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createInsert() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('INSERT INTO products (name) VALUES (name)');

        $this->assertSame('products', Table::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function aliasInsert() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('INSERT INTO products (name) VALUES (name)');

        $this->assertSame(null, Table::alias($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createInsertWithUrl() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('INSERT INTO "http://www.circle.ai/api" (name) VALUES (name)');

        $this->assertSame('http://www.circle.ai/api', Table::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function aliasInsertWithUrl() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('INSERT INTO "http://www.circle.ai/api" (name) VALUES (name)');

        $this->assertSame(null, Table::alias($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createUpdate() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('UPDATE products p0 set name="name"');

        $this->assertSame('products', Table::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function aliasUpdate() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('UPDATE products p0 set name="name"');

        $this->assertSame('p0', Table::alias($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::create
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function createUpdateWithUrl() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('UPDATE "http://www.circle.ai/api" p0 set name="name"');

        $this->assertSame('http://www.circle.ai/api', Table::create($tokens));
    }

    /**
     * @test
     * @group  unit
     * @covers ::alias
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function aliasUpdateWithUrl() {
        $parser = new PHPSQLParser();
        $tokens = $parser->parse('UPDATE "http://www.circle.ai/api" p0 set name="name"');

        $this->assertSame('p0', Table::alias($tokens));
    }
}