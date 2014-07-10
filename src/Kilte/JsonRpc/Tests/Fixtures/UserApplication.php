<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests\Fixtures;

/**
 * Class UserApplication
 *
 * @package Kilte\JsonRpc\Tests\Fixtures
 */
class UserApplication
{

    /**
     * Says hello
     *
     * @param string $name Name
     *
     * @return string
     */
    public function greet($name)
    {
        return sprintf('hello, %s', $name);
    }

}
