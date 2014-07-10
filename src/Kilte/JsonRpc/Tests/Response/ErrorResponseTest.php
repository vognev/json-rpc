<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests\Response;

use Kilte\JsonRpc\Exception\InternalException;
use Kilte\JsonRpc\Response\Json\ErrorResponse;

/**
 * Class ErrorResponseTest
 *
 * @package Kilte\JsonRpc\Tests\Response
 */
class ErrorResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonify()
    {
        $json = [
            'jsonrpc' => '2.0',
            'error'   => [
                'code'    => -32603,
                'message' => 'Internal JSON-RPC error'
            ],
            'id'      => 1
        ];
        $expected = json_encode($json);
        $this->assertEquals($expected, (new ErrorResponse(1, new InternalException()))->jsonify());
        $json['error']['data'] = 'additional data';
        $expected = json_encode($json);
        $this->assertEquals($expected, (new ErrorResponse(1, new InternalException(), 'additional data'))->jsonify());
    }

}
