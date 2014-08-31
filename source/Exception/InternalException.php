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
 * Class InternalException
 *
 * @package Kilte\JsonRpc\Exception
 */
class InternalException extends JsonRpcException
{

    protected $code = -32603;
    protected $message = 'Internal JSON-RPC error';

}
