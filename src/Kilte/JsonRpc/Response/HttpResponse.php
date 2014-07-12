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
     * @var AbstractResponse[] Responses
     */
    private $responses;

    /**
     * @var boolean Is batch?
     */
    private $isBatch;

    /**
     * Constructor
     *
     * @param AbstractResponse[] $responses Responses
     * @param boolean            $isBatch   Is batch?
     *
     * @return self
     */
    public function __construct(array $responses, $isBatch = false)
    {
        $this->responses = $responses;
        $this->isBatch = $isBatch;
    }

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        header('Content-Type: application/json', true);
        if (sizeof($this->responses) == 1 && $this->isBatch === false) {
            $output = $this->responses[0]->jsonify();
        } else {
            $output = sprintf(
                '[%s]',
                implode(
                    ',',
                    array_map(
                        function (AbstractResponse $response) {
                            return $response->jsonify();
                        },
                        $this->responses
                    )
                )
            );
        }
        echo $output;
    }

}
