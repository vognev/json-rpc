<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Request;

/**
 * Class Request
 *
 * Represents a JSONRPC Request object
 *
 * @package Kilte\JsonRpc\Request
 */
class Request
{

    /**
     * A String specifying the version of the JSON-RPC protocol. MUST be exactly "2.0".
     */
    const JSONRPC = "2.0";

    /**
     * An identifier established by the Client that MUST contain a String, Number, or NULL value if included.
     * If it is not included it is assumed to be a notification.
     * The value SHOULD normally not be Null and Numbers SHOULD NOT contain fractional parts.
     *
     * @var string|int|null|boolean
     */
    private $id;

    /**
     * A String containing the name of the method to be invoked.
     * Method names that begin with the word rpc followed by a period character (U+002E or ASCII 46)
     * are reserved for rpc-internal methods and extensions and MUST NOT be used for anything else.
     *
     * @var string
     */
    private $method;

    /**
     * A Structured value that holds the parameter values to be used during the invocation of the method.
     * This member MAY be omitted.
     *
     * @var array
     */
    private $params;

    /**
     * Constructor
     *
     * @param string                  $method Name of the method to be invoked
     * @param array                   $params The parameter values to be used during the invocation of the method
     * @param string|int|null|boolean $id     An identifier established by the Client
     *                                        (If false given, then it is a notification)
     *
     * @return self
     */
    public function __construct($method, array $params = [], $id = null)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }

    /**
     * Returns the JSON representation of a request
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            [
                'jsonrpc' => self::JSONRPC,
                'id'      => $this->id,
                'method'  => $this->method,
                'params'  => $this->params
            ]
        );
    }

    /**
     * Returns method name
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns ID
     *
     * @return int|null|string|boolean
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

}
