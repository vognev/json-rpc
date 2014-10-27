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
 * Class SuccessResponse
 *
 * @package Kilte\JsonRpc\Response\Json
 */
class SuccessResponse extends AbstractResponse
{

    /**
     * The value of this member is determined by the method invoked on the Server.
     *
     * @var mixed
     */
    protected $result;

    /**
     * Constructor
     *
     * @param string|int|null $id     Identifier
     * @param mixed           $result The value returned by the method invoked on the Server.
     *
     * @return self
     */
    public function __construct($id, $result)
    {
        $this->id = $id;
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonify()
    {
        return json_encode(array('jsonrpc' => self::JSONRPC, 'result' => $this->result, 'id' => $this->id));
    }

}
