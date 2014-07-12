<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests\Response;

use Kilte\JsonRpc\Response\HttpResponse;
use Kilte\JsonRpc\Response\Json\SuccessResponse;

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
        $response = new SuccessResponse('id', 'result');
        ob_start();
        (new HttpResponse())->send($response->jsonify());
        $actual = ob_get_clean();
        $this->assertEquals($response->jsonify(),$actual);
    }

}
