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
 * Class InvalidParamsException
 *
 * @package Kilte\JsonRpc\Exception
 */
class InvalidParamsException extends JsonRpcException
{

    protected $code = -32602;
    protected $message = 'Invalid method parameter(s)';

}
