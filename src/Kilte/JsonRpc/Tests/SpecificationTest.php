<?php

namespace Kilte\JsonRpc\Tests;

use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Response\HttpResponse;
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
        $app = [];
        $app['subtract'] = function ($a, $b) {
            return $a - $b;
        };
        $app['update'] = function ($a, $b, $c, $e, $d) {
        };
        $app['baz'] = function () {
        };
        $app['notify_sum'] = function ($a, $b, $c) {
        };
        $app['notify_hello'] = function ($a) {
        };
        $app['sum'] = function ($a, $b, $c) {
            return $a + $b + $c;
        };
        $app['get_data'] = function () {
            return ['hello', 5];
        };
        $ioStream = new IOStreamFactory();
        $response = new HttpResponse();
        $server = new Server(new Application($app), $ioStream, $response);
        $streamPath = __DIR__ . '/Fixtures/Specification/%s';
        $invalidRequest = [
            'jsonrpc' => '2.0',
            'error'   => [
                'code'    => -32600,
                'message' => 'The JSON sent is not a valid Request object.'
            ],
            'id'      => null
        ];
        $parseError = [
            'jsonrpc' => '2.0',
            'error'   => [
                'code'    => -32700,
                'message' => 'An error occurred on the server while parsing the JSON text.'
            ],
            'id'      => null
        ];
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
            '05_invalid_json.txt'                 => $parseError,
            '06_invalid_request_object.json'      => $invalidRequest,
            '07_empty_array.json'                 => $invalidRequest,
            '08_notification_without_params.json' => '',
            '09_batch_notifications.json'         => '',
            '10_batch.json'                       => [
                ['jsonrpc' => '2.0', 'result' => 7, 'id' => '1'],
                ['jsonrpc' => '2.0', 'result' => 19, 'id' => '2'],
                [
                    'jsonrpc' => '2.0',
                    'error'   => [
                        'code'    => -32600,
                        'message' => 'The JSON sent is not a valid Request object.'
                    ],
                    'id'      => null
                ],
                [
                    'jsonrpc' => '2.0',
                    'error'   => ['code' => -32601, 'message' => 'Method "foo.get" does not exists'],
                    'id'      => '5'
                ],
                ['jsonrpc' => '2.0', 'result' => ['hello', 5], 'id' => '9']
            ],
            '11_invalid_json_batch.txt'           => $parseError,
            '12_invalid_batch.json'               => [
                [
                    'jsonrpc' => '2.0',
                    'error'   => [
                        'code'    => -32600,
                        'message' => 'The JSON sent is not a valid Request object.'
                    ],
                    'id'      => null
                ],
                [
                    'jsonrpc' => '2.0',
                    'error'   => [
                        'code'    => -32600,
                        'message' => 'The JSON sent is not a valid Request object.'
                    ],
                    'id'      => null
                ],
                [
                    'jsonrpc' => '2.0',
                    'error'   => [
                        'code'    => -32600,
                        'message' => 'The JSON sent is not a valid Request object.'
                    ],
                    'id'      => null
                ],
            ],
            '13_not_empty_invalid_batch.json'     => [
                [
                    'jsonrpc' => '2.0',
                    'error'   => [
                        'code'    => -32600,
                        'message' => 'The JSON sent is not a valid Request object.'
                    ],
                    'id'      => null
                ],
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
