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
 * Class JsonRpcException
 *
 * @package Kilte\JsonRpc\Exception
 */
class JsonRpcException extends \RuntimeException
{
    protected $data = null;

    public function __construct($message = null, $data = null)
    {
        if (null !== $message)
            $this->message = $message;
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
