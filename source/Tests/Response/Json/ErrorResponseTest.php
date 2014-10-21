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

namespace Kilte\JsonRpc\Tests\Response\Json;

use Kilte\JsonRpc\Exception\InternalException;
use Kilte\JsonRpc\Response\Json\ErrorResponse;

/**
 * ErrorResponse Test
 *
 * @package Kilte\JsonRpc\Tests\Response\Json
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
        $this->assertEquals($expected, (new ErrorResponse(1, new InternalException(null, 'additional data')))->jsonify());
    }

}
