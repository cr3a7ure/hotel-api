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

namespace Circle\DoctrineRestDriver\Exceptions;

/**
 * Exception class for methods that are not implemented.
 * Used if a method must exist because of interface contract,
 * but has no functionality implemented yet.
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class MethodNotImplementedException extends \Exception {

    /**
     * InvalidSqlOperationException constructor
     *
     * @param string $class
     * @param string $method
     */
    public function __construct($class, $method) {
        parent::__construct('The method ' . $method . ' in class ' . $class . ' is not implemented');
    }
}
