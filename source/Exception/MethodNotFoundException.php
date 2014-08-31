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
 * Class MethodNotFoundException
 *
 * @package Kilte\JsonRpc\Exception
 */
class MethodNotFoundException extends JsonRpcException
{

    protected $code = -32601;
    protected $message = 'The method does not exist / is not available.';

}
