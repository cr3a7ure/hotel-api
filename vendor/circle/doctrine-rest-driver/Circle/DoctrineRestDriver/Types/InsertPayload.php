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

use Circle\DoctrineRestDriver\Validation\Assertions;

/**
 * InsertPayload type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class InsertPayload {

    /**
     * Converts the string with format (key) VALUES (value)
     * into json
     *
     * @param  array $tokens
     * @return string
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public static function create(array $tokens) {
        Assertions::assertHashMap('tokens', $tokens);

        $columns = array_filter($tokens['INSERT'], function($token) {
            return $token['expr_type'] === 'column-list';
        });

        $columns = explode(',', str_replace(['(', ')', ' '], '', end($columns)['base_expr']));
        $values  = explode(',', str_replace(['(', ')', ' '], '', end($tokens['VALUES'])['base_expr']));

        return json_encode(array_combine($columns, array_map(function($value) {
            return Value::create($value);
        }, $values)));
    }
}