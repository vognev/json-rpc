<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Response;

use Kilte\JsonRpc\Response\Json\AbstractResponse;

/**
 * Class HttpResponse
 *
 * @package Kilte\JsonRpc\Response
 */
class HttpResponse implements ResponseInterface
{
    /**
     * @var AbstractResponse
     */
    private $response;

    /**
     * @param AbstractResponse $response
     */
    public function __construct(AbstractResponse $response)
    {
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        header('Content-Type: application/json', true);
        echo $this->response->jsonify();
    }

}
