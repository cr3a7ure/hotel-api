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
 * Payload type: Union type for UpdatePayload and InsertPayload
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class Payload {

    /**
     * Returns an InsertPayload::create or UpdatePayload::create
     * result or null depending on the given tokens array
     *
     * @param  array       $tokens
     * @return null|string
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public static function create(array $tokens) {
        Assertions::assertHashMap('tokens', $tokens);

        $operation = SqlOperation::create($tokens);
        if ($operation === SqlOperations::SELECT || $operation === SqlOperations::DELETE) return null;
        return $operation === SqlOperations::INSERT ? InsertPayload::create($tokens) : UpdatePayload::create($tokens);
    }
}