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

namespace Circle\DoctrineRestDriver\Tests\Validation\Exceptions;

use Circle\DoctrineRestDriver\Validation\Exceptions\InvalidTypeException;

/**
 * Tests the invalid type exception
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Validation\Exceptions\InvalidTypeException
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class InvalidTypeExceptionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     */
    public function construct() {
        $exception = new InvalidTypeException('string', 'someKey', 'someValue');
        $this->assertSame('The given value someValue for "someKey" is not of type string', $exception->getMessage());
    }
}