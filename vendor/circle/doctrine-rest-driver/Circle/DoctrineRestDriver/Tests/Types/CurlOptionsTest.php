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

use Circle\DoctrineRestDriver\Types\CurlOptions;

/**
 * Tests the curl options
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\CurlOptions
 */
class CurlOptionsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $expected;

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        $this->options = [
            'security_strategy'  => 'none',
            'CURLOPT_MAXREDIRS'  => 22,
            'CURLOPT_HTTPHEADER' => ['Content-Type: text/plain']
        ];

        $this->expected = [
            CURLOPT_HTTPHEADER     => ['Content-Type: text/plain'],
            CURLOPT_MAXREDIRS      => 22,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_CONNECTTIMEOUT => 25,
            CURLOPT_CRLF           => true,
            CURLOPT_SSLVERSION     => 3,
            CURLOPT_FOLLOWLOCATION => true,
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
        $this->assertEquals($this->expected, CurlOptions::create($this->options));
    }
}
