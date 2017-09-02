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

namespace Circle\DoctrineRestDriver\Tests\Factory;

use Circle\DoctrineRestDriver\Factory\RequestFactory;
use Circle\DoctrineRestDriver\Types\Request;
use PHPSQLParser\PHPSQLParser;

/**
 * Tests the restclient factory
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Factory\RequestFactory
 */
class RequestFactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var array
     */
    private $requestOptions = [
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_MAXREDIRS      => 25,
        CURLOPT_TIMEOUT        => 25,
        CURLOPT_CONNECTTIMEOUT => 25,
        CURLOPT_CRLF           => true,
        CURLOPT_SSLVERSION     => 3,
        CURLOPT_FOLLOWLOCATION => true,
    ];

    /**
     * @var array
     */
    private $factoryOptions = [
        'CURLOPT_HTTPHEADER'     => 'Content-Type: application/json',
        'CURLOPT_MAXREDIRS'      => 25,
        'CURLOPT_TIMEOUT'        => 25,
        'CURLOPT_CONNECTTIMEOUT' => 25,
        'CURLOPT_CRLF'           => true,
        'CURLOPT_SSLVERSION'     => 3,
        'CURLOPT_FOLLOWLOCATION' => true,
    ];

    /**
     * @test
     * @group  unit
     * @covers ::createOne
     */
    public function createOne() {
        $query    = 'SELECT name FROM products WHERE id=1';
        $parser   = new PHPSQLParser();
        $factory  = new RequestFactory();
        $expected = new Request('get', 'http://circle.ai/products/1', $this->requestOptions);

        $this->assertEquals($expected, $factory->createOne($parser->parse($query), 'http://circle.ai', $this->factoryOptions));
    }
}