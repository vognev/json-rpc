<?php

namespace Kilte\JsonRpc\Response;

use Kilte\ReflectionFactory\Factory;

/**
 * Class ResponseFactory
 *
 * @package Kilte\JsonRpc\Response
 */
class ResponseFactory extends Factory
{

    /**
     * Constructor
     *
     * @param array $classes Responses
     *
     * @return self
     */
    public function __construct(array $classes = [])
    {
        parent::__construct('\\Kilte\\JsonRpc\\Response\\ResponseInterface', $classes);
    }

}
