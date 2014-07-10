<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests;

use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Exception\InternalException;
use Kilte\JsonRpc\Exception\MethodNotFoundException;
use Kilte\JsonRpc\Exception\ParseException;
use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Response\Json\ErrorResponse;
use Kilte\JsonRpc\Response\Json\SuccessResponse;
use Kilte\JsonRpc\Response\ResponseFactory;
use Kilte\JsonRpc\Server;

/**
 * Class ServerTest
 *
 * @package Kilte\JsonRpc\Tests
 */
class ServerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns server instance
     *
     * @param array|object $app User application
     *
     * @return Server
     */
    private function makeServer($app, $stream)
    {
        $app = new Application($app);
        $requestFactory = new IOStreamFactory();
        $requestFactory->setStream($stream);
        $responseFactory = new ResponseFactory();
        $responseFactory->add('http', '\\Kilte\\JsonRpc\\Response\\HttpResponse');

        return new Server($app, $requestFactory, $responseFactory, 'http');
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandle()
    {
        $app = [];
        $app['method'] = function ($arg) {
            return sprintf('Argument value is %s', $arg);
        };
        $server = $this->makeServer($app, __DIR__ . '/Fixtures/stream.json');
        ob_start();
        $server->handle();
        $actualResponse = ob_get_clean();
        $expectedResponse = (new SuccessResponse('id', 'Argument value is arg'))->jsonify();
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandleErrorResponseWhileHandlingRequest()
    {
        $server = $this->makeServer([], __DIR__ . '/Fixtures/invalid_json.txt');
        ob_start();
        $server->handle();
        $actualResponse = ob_get_clean();
        $expectedResponse = (new ErrorResponse(null, new ParseException()))->jsonify();
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandleErrorResponseMethodNotFound()
    {
        $server = $this->makeServer([], __DIR__ . '/Fixtures/stream.json');
        ob_start();
        $server->handle();
        $actualResponse = ob_get_clean();
        $e = new MethodNotFoundException("Method \"method\" does not exists");
        $expectedResponse = (new ErrorResponse('id', $e))->jsonify();
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandleErrorResponseMethodThrowsJsonRpcException()
    {
        $app = [];
        $app['method'] = function ($arg) {
            throw new InternalException($arg);
        };
        $server = $this->makeServer($app, __DIR__ . '/Fixtures/stream.json');
        ob_start();
        $server->handle();
        $actualResponse = ob_get_clean();
        $expectedResponse = (new ErrorResponse('id', new InternalException('arg')))->jsonify();
        $this->assertEquals($expectedResponse, $actualResponse);
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandleErrorResponseMethodThrowsAnyException()
    {
        $app = [];
        $app['method'] = function ($arg) {
            throw new \Exception($arg);
        };
        $server = $this->makeServer($app, __DIR__ . '/Fixtures/stream.json');
        ob_start();
        $server->handle();
        $actualResponse = ob_get_clean();
        $expectedResponse = (new ErrorResponse('id', new InternalException('arg')))->jsonify();
        $this->assertEquals($expectedResponse, $actualResponse);
    }

}
