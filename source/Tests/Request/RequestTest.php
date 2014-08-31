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

namespace Kilte\JsonRpc\Tests\Request;

use Kilte\JsonRpc\Request\Request;

/**
 * Request Test
 *
 * @package Kilte\JsonRpc\Tests\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns request instance
     *
     * @return Request
     */
    private function getRequest()
    {
        return new Request('method', ['arg'], 'id');
    }

    public function testGetMethod()
    {
        $this->assertEquals('method', $this->getRequest()->getMethod());
    }

    public function testGetId()
    {
        $this->assertEquals('id', $this->getRequest()->getId());
    }

    public function testGetParams()
    {
        $this->assertEquals(['arg'], $this->getRequest()->getParams());
    }

    public function testToString()
    {
        $this->assertEquals(
            json_encode(['jsonrpc' => '2.0', 'id' => 'id', 'method' => 'method', 'params' => ['arg']]),
            (string) $this->getRequest()
        );
    }

}
