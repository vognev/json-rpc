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

namespace Kilte\JsonRpc\Tests;

use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Exception\InternalException;
use Kilte\JsonRpc\Exception\MethodNotFoundException;
use Kilte\JsonRpc\Exception\ParseException;
use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Request\Request;
use Kilte\JsonRpc\Response\Json\ErrorResponse;
use Kilte\JsonRpc\Response\Json\SuccessResponse;
use Kilte\JsonRpc\Server;

/**
 * Server Test
 *
 * @package Kilte\JsonRpc\Tests
 */
class ServerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns server instance
     *
     * @param array|object $app    User application
     * @param mixed        $stream Stream
     *
     * @return Server
     */
    private function makeServer($app, $stream)
    {
        $app = new Application($app);
        $requestFactory = new IOStreamFactory();
        $requestFactory->setStream($stream);

        return new Server($app, $requestFactory);
    }

    public function testHandle()
    {
        $app = [];
        $app['method'] = function ($arg) {
            return sprintf('Argument value is %s', $arg);
        };
        $server = $this->makeServer($app, __DIR__ . '/Fixtures/stream.json');
        $expectedResponse = (new SuccessResponse('id', 'Argument value is arg'))->jsonify();
        $this->assertEquals($expectedResponse, $server->handle());
    }

    public function testHandleErrorResponseWhileHandlingRequest()
    {
        $server = $this->makeServer([], __DIR__ . '/Fixtures/invalid_json.txt');
        $expectedResponse = (new ErrorResponse(null, new ParseException()))->jsonify();
        $this->assertEquals($expectedResponse, $server->handle());
    }

    public function testHandleErrorResponseMethodNotFound()
    {
        $server = $this->makeServer([], __DIR__ . '/Fixtures/stream.json');
        $e = new MethodNotFoundException("Method \"method\" does not exists");
        $expectedResponse = (new ErrorResponse('id', $e))->jsonify();
        $this->assertEquals($expectedResponse, $server->handle());
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
        $expectedResponse = (new ErrorResponse('id', new InternalException('arg')))->jsonify();
        $this->assertEquals($expectedResponse, $server->handle());
    }

    public function testHandleErrorResponseMethodThrowsAnyException()
    {
        $app = [];
        $app['method'] = function ($arg) {
            throw new \Exception($arg);
        };
        $server = $this->makeServer($app, __DIR__ . '/Fixtures/stream.json');
        $expectedResponse = (new ErrorResponse('id', new InternalException('arg')))->jsonify();
        $this->assertEquals($expectedResponse, $server->handle());
    }

    public function testMiddleware()
    {
        $app = [];
        $app['method'] = function ($id) {
            return 'value_' . $id;
        };
        $server = $this->makeServer($app, __DIR__ . '/Fixtures/stream.json');
        $server->before(
            function (Request $request) {
                $this->assertEquals($request->getMethod(), 'method');
            }
        );
        $this->assertEquals((new SuccessResponse('id', 'value_arg'))->jsonify(), $server->handle());

        $this->setExpectedException('\\InvalidArgumentException', 'Expects callable, array given');
        $server->before([]);
    }

}
