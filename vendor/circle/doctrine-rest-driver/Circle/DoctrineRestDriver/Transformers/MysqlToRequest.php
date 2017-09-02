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

namespace Circle\DoctrineRestDriver\Transformers;

use Circle\DoctrineRestDriver\Factory\RequestFactory;
use Circle\DoctrineRestDriver\Types\Request;
use Circle\DoctrineRestDriver\Validation\Assertions;
use PHPSQLParser\PHPSQLParser;

/**
 * Transforms a given sql query to a corresponding request
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class MysqlToRequest {

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var PHPSQLParser
     */
    private $parser;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var string
     */
    private $options;

    /**
     * MysqlToRequest constructor
     *
     * @param array $options
     */
    public function __construct(array $options) {
        $this->apiUrl         = $options['host'];
        $this->options        = $options['driverOptions'];
        $this->parser         = new PHPSQLParser();
        $this->requestFactory = new RequestFactory();
    }

    /**
     * Transforms the given query into a request object
     *
     * @param  string $query
     * @param  array  $params
     * @return Request
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function transform($query, array $params = []) {
        $query = array_reduce($params, function($query, $param) {
            return strpos($query, '?') ? substr_replace($query, $param, strpos($query, '?'), strlen('?')) : $query;
        }, $query);

        $queryParts = explode(' ', $query);
        $transformedQuery = array_reduce($queryParts, function($carry, $part) {
            return $carry . (Assertions::isUrl($part) ? ('"' . $part . '" ') : ($part . ' '));
        });

        return $this->requestFactory->createOne($this->parser->parse($transformedQuery), $this->apiUrl, $this->options);
    }
}