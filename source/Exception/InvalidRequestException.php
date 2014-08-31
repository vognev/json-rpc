<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
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
