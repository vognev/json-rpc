<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Exception;

/**
 * Class MethodNotFoundException
 *
 * @package Kilte\JsonRpc\Exception
 */
class MethodNotFoundException extends JsonRpcException
{

    protected $code = -32601;
    protected $message = 'The method does not exist / is not available.';

}
