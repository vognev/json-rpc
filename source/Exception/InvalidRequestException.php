<?php

/**
 * Part of the Kilte\JsonRpc
 *
 * For the full copyright and license information,
 * view the LICENSE file that was distributed with this source code.
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package Kilte\JsonRpc
 */

namespace Kilte\JsonRpc\Exception;

/**
 * Class InvalidRequestException
 *
 * @package Kilte\JsonRpc\Exception
 */
class InvalidRequestException extends JsonRpcException
{

    protected $code = -32600;
    protected $message = 'The JSON sent is not a valid Request object.';

}
