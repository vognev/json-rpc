<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests\Response;

use Kilte\JsonRpc\Response\HttpResponse;

/**
 * Class HttpResponseTest
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
