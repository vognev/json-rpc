<?php

namespace Kilte\JsonRpc\Tests;

use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Response\ResponseFactory;
use Kilte\JsonRpc\Server;

/**
 * Class SpecificationTest
 *
 * Test that we conform specification
 *
 * @package Kilte\JsonRpc\Tests
 */
class SpecificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testSpecification()
    {
        $this->markTestSkipped('Batch not implemented');
        $app = [];
        $app['subtract'] = function ($a, $b) {
            return $a - $b;
        };
        $app['update'] = function ($a, $b, $c, $e, $d) {
            return $a + $b + $c + $e + $d;
        };
        $app['baz'] = function () {
            return null;
        };
        $ioStream = new IOStreamFactory();
        $response = new ResponseFactory();
        $response->add('http', '\\Kilte\\JsonRpc\\Response\\HttpResponse');
        $server = new Server(new Application($app), $ioStream, $response, 'http');
        $streamPath = __DIR__ . '/Fixtures/Specification/%s';
        $fixtures = [
            '01_positional_params.json'           => ['jsonrpc' => '2.0', 'result' => 19, 'id' => 1],
            '02_named_params.json'                => ['jsonrpc' => '2.0', 'result' => -19, 'id' => 3],
            '03_notification_with_params.json'    => '',
            '04_non_existent_method.json'         => [
                'jsonrpc' => '2.0',
                'error'   => [
                    'code'    => -32601,
                    'message' => 'Method "foobar" does not exists'
                ],
                'id'      => 1
            ],
            '05_invalid_json.txt'                 => [
                'jsonrpc' => '2.0',
                'error'   => [
                    'code'    => -32700,
                    'message' => 'An error occurred on the server while parsing the JSON text.'
                ],
                'id'      => null
            ],
            '06_invalid_request_object.json'      => [
                'jsonrpc' => '2.0',
                'error'   => [
                    'code'    => -32600,
                    'message' => 'The JSON sent is not a valid Request object.'
                ],
                'id'      => null
            ],
            '07_empty_array.json'                 => [
                'jsonrpc' => '2.0',
                'error'   => [
                    'code'    => -32600,
                    'message' => 'The JSON sent is not a valid Request object.'
                ],
                'id'      => null
            ],
            '08_notification_without_params.json' => '',
            '09_batch_notifications.json'         => '',
            '10_batch.json'                       => [
                ['jsonrpc' => '2.0', 'result' => 7, 'id' => '1'],
                ['jsonrpc' => '2.0', 'result' => 19, 'id' => '2'],
                ['jsonrpc' => '2.0', 'error' => ['code' => -32600, 'message' => 'Invalid Request'], 'id' => null],
                ['jsonrpc' => '2.0', 'error' => ['code' => -32601, 'message' => 'Method not found'], 'id' => '5'],
                ['jsonrpc' => '2.0', 'result' => ['hello', 5], 'id' => '9']
            ],
            '11_invalid_json_batch.txt'           => [
                'jsonrpc' => '2.0',
                'error'   => [
                    'code'    => -32700,
                    'message' => 'Parse error'
                ],
                'id'      => null
            ],
            '12_invalid_batch.json' => [
                ['jsonrpc' => '2.0', 'error' => ['code' => -32600, 'message' => 'Invalid Request'], 'id' => null],
                ['jsonrpc' => '2.0', 'error' => ['code' => -32600, 'message' => 'Invalid Request'], 'id' => null],
                ['jsonrpc' => '2.0', 'error' => ['code' => -32600, 'message' => 'Invalid Request'], 'id' => null],
            ],
            '12_not_empty_invalid_batch.json' => [
                ['jsonrpc' => '2.0', 'error' => ['code' => -32600, 'message' => 'Invalid Request'], 'id' => null],
            ]
        ];
        foreach ($fixtures as $request => $expectedResponse) {
            $ioStream->setStream(sprintf($streamPath, $request));
            ob_start();
            $server->handle();
            $this->assertEquals(
                !empty($expectedResponse) ? json_encode($expectedResponse) : '',
                ob_get_clean(),
                'Request: ' . file_get_contents(sprintf($streamPath, $request))
            );
        }

    }

}
