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

namespace Circle\DoctrineRestDriver\Tests\Security;

use Circle\DoctrineRestDriver\Enums\HttpMethods;
use Circle\DoctrineRestDriver\Security\HttpAuthentication;
use Circle\DoctrineRestDriver\Types\Request;

/**
 * Tests the result mapping
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Security\HttpAuthentication
 */
class HttpAuthenticationTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var HttpAuthentication
     */
    private $authentication;

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        $this->authentication = new HttpAuthentication([
            'host'          => 'http://circle.ai',
            'user'          => 'Aladdin',
            'password'      => 'OpenSesame',
            'driverOptions' => []
        ]);
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::transformRequest
     */
    public function transformRequest() {
        $expectedOptions = [
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic QWxhZGRpbjpPcGVuU2VzYW1l'
            ]
        ];

        $request  = new Request(HttpMethods::GET, 'http://circle.ai', []);
        $expected = new Request(HttpMethods::GET, 'http://circle.ai', $expectedOptions);

        $this->assertEquals($expected, $this->authentication->transformRequest($request));
    }
}