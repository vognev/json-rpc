<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Exception;

/**
 * Class ParseException
 *
 * @package Kilte\JsonRpc\Exception
 */
class ParseException extends JsonRpcException
{

    protected $code = -32700;
    protected $message = 'An error occurred on the server while parsing the JSON text.';

}
