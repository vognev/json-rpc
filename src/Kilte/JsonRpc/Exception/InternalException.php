<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
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
