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

namespace Kilte\JsonRpc\Tests\Response;

use Kilte\JsonRpc\Response\HttpResponse;

/**
 * HttpResponse Test
 *
 * @package Kilte\JsonRpc\Tests\Response
 */
class HttpResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testSend()
    {
        $response = 'response content';
        ob_start();
        (new HttpResponse($response))->send();
        $actual = ob_get_clean();
        $this->assertEquals($response,$actual);
    }

    public function testGetContent()
    {
        $response = 'response content';
        $this->assertEquals($response, (new HttpResponse($response))->getContent());
    }

}
