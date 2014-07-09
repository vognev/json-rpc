<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests\Response;

use Kilte\JsonRpc\Response\Json\SuccessResponse;

/**
 * Class SuccessResponseTest
 *
 * @package Kilte\JsonRpc\Tests\Response
 */
class SuccessResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonify()
    {
        $expected = json_encode(['jsonrpc' => '2.0', 'result' => 'returned_data', 'id' => 1]);
        $this->assertEquals($expected, (new SuccessResponse(1, 'returned_data'))->jsonify());
    }

}
