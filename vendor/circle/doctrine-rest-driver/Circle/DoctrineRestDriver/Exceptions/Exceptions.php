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

use Circle\DoctrineRestDriver\Types\Request;
use Circle\DoctrineRestDriver\Validation\Exceptions\InvalidTypeException;
use Circle\DoctrineRestDriver\Validation\Exceptions\NotNilException;

/**
 * This trait contains methods to throw exceptions
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 * @SuppressWarnings("PHPMD.CamelCaseMethodName")
 */
class Exceptions {

    /**
     * throws an invalid type exception
     *
     * @param  string $expectedType
     * @param  string $key
     * @param  mixed  $value
     * @throws InvalidTypeException
     */
    public static function InvalidTypeException($expectedType, $key, $value) {
        throw new InvalidTypeException($expectedType, $key, $value);
    }

    /**
     * throws a not nil exception
     *
     * @param  string $varName
     * @throws NotNilException
     */
    public static function NotNilException($varName) {
        throw new NotNilException($varName);
    }

    /**
     * throws an unsupported fetch mode exception
     *
     * @param  int $fetchMode
     * @throws UnsupportedFetchModeException
     */
    public static function UnsupportedFetchModeException($fetchMode) {
        throw new UnsupportedFetchModeException($fetchMode);
    }

    /**
     * throws an invalid sql operation exception
     *
     * @param  string $operation
     * @throws InvalidSqlOperationException
     */
    public static function InvalidSqlOperationException($operation) {
        throw new InvalidSqlOperationException($operation);
    }

    /**
     * throws an invalid auth strategy exception
     *
     * @param  string $class
     * @throws InvalidAuthStrategyException
     */
    public static function InvalidAuthStrategyException($class) {
        throw new InvalidAuthStrategyException($class);
    }

    /**
     * throws a method not implemented exception
     *
     * @param  string $class
     * @param  string $method
     * @throws MethodNotImplementedException
     */
    public static function MethodNotImplementedException($class, $method) {
        throw new MethodNotImplementedException($class, $method);
    }

    /**
     * throws a request failed exception
     *
     * @param  Request $request
     * @param  int     $errorCode
     * @param  string  $errorMessage
     * @throws RequestFailedException
     */
    public static function RequestFailedException(Request $request, $errorCode, $errorMessage) {
        throw new RequestFailedException($request, $errorCode, $errorMessage);
    }
}
