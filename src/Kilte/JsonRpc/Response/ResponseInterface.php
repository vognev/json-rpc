<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Response;

/**
 * Interface ResponseInterface
 *
 * @package Kilte\JsonRpc\Response
 */
interface ResponseInterface
{

    /**
     * Sends a response
     *
     * @return void
     */
    public function send();

} 