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

namespace Kilte\JsonRpc\Response\Json;

/**
 * Class AbstractResponse
 *
 * @package Kilte\JsonRpc\Response\Json
 */
abstract class AbstractResponse
{

    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     */
    const JSONRPC = '2.0';

    /**
     * It MUST be the same as the value of the id member in the Request Object.
     * If there was an error in detecting the id in the Request object
     * (e.g. Parse error/Invalid Request), it MUST be Null.
     *
     * @var string|int|null
     */
    protected $id;

    /**
     * Returns JSON for response
     *
     * @return string
     */
    abstract public function jsonify();

}
