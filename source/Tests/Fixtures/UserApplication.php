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

    /**
     * dot.separated
     *
     * @return string
     */
    public function dotSeparated()
    {
        return 'dot.separated.response';
    }

}
