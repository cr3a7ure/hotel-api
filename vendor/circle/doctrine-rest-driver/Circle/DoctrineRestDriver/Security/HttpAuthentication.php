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

namespace Circle\DoctrineRestDriver\Security;

use Circle\DoctrineRestDriver\Types\Request;

/**
 * This file can be used in the driver's config to use
 * the target API with basic http authentication
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class HttpAuthentication implements AuthStrategy {

    /**
     * @var array
     */
    private $config;

    /**
     * HttpBasicAuthentication constructor
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function transformRequest(Request $request) {
        $options  = $request->getCurlOptions();
        $headers  = empty($options[CURLOPT_HTTPHEADER]) ? [] : $options[CURLOPT_HTTPHEADER];
        array_push($headers, 'Authorization: Basic ' . base64_encode($this->config['user'] . ':' . $this->config['password']));
        $options[CURLOPT_HTTPHEADER] = $headers;

        return new Request($request->getMethod(), $request->getUrl(), $options, $request->getQuery(), $request->getPayload());
    }
}