<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Response;

/**
 * Class HttpResponse
 *
 * @package Kilte\JsonRpc\Response
 */
class HttpResponse implements ResponseInterface
{

    /**
     * {@inheritdoc}
     */
    public function send($output)
    {
        header('Content-Type: application/json', true);
        echo $output;
    }

}
