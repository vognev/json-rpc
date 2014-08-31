<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
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
