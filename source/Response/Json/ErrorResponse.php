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

use Kilte\JsonRpc\Exception\JsonRpcException;

/**
 * Class ErrorResponse
 *
 * @package Kilte\JsonRpc\Response\Json
 */
class ErrorResponse extends AbstractResponse
{

    /**
     * A Number that indicates the error type that occurred.
     * @var int
     */
    protected $code;

    /**
     * A String providing a short description of the error.
     * The message SHOULD be limited to a concise single sentence.
     * @var string
     */
    protected $message;

    /**
     * A Primitive or Structured value that contains additional information about the error.
     * This may be omitted.
     * The value of this member is defined by the Server (e.g. detailed error information, nested errors etc.).
     *
     * @var mixed
     */
    protected $data;

    /**
     * Constructor
     *
     * @param string|int|null  $id   Identifier
     * @param JsonRpcException $e    Exception instance
     * @param mixed            $data Additional information about the error
     *
     * @return self
     */
    public function __construct($id, JsonRpcException $e, $data = null)
    {
        $this->id = $id;
        $this->code = $e->getCode();
        $this->message = $e->getMessage();
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonify()
    {
        $response = [
            'jsonrpc' => self::JSONRPC,
            'error' => [
                'code' => $this->code,
                'message' => $this->message
            ],
            'id' => $this->id
        ];
        if ($this->data !== null) {
            $response['error']['data'] = $this->data;
        }

        return json_encode($response);
    }

}
