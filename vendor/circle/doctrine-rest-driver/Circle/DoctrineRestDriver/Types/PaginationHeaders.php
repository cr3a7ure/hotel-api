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

namespace Circle\DoctrineRestDriver\Types;

use Circle\DoctrineRestDriver\Enums\SqlOperations;
use Circle\DoctrineRestDriver\Validation\Assertions;

/**
 * Handles creation of pagination related http headers
 *
 * @author    Djane Rey Mabelin <thedjaney@gmail.com>
 * @copyright 2016
 */
class PaginationHeaders {

    /**
     * Returns Limit and Offset headers
     *
     * @param  array $tokens
     * @return string
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public static function create(array $tokens) {
        Assertions::assertHashMap('tokens', $tokens);

        if (empty($tokens['LIMIT'])) return [];

        $headers = [];
        array_push($headers, 'Limit: ' . $tokens['LIMIT']['rowcount']);
        if(empty($tokens['LIMIT']['offset'])) return $headers;
        array_push($headers, 'Offset: ' . $tokens['LIMIT']['offset']);
        return $headers;
    }
}
