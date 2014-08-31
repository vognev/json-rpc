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
 * Class ParseException
 *
 * @package Kilte\JsonRpc\Exception
 */
class ParseException extends JsonRpcException
{

    protected $code = -32700;
    protected $message = 'An error occurred on the server while parsing the JSON text.';

}
