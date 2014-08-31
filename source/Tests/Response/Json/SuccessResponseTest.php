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

use Kilte\JsonRpc\Response\Json\SuccessResponse;

/**
 * SuccessResponse Test
 *
 * @package Kilte\JsonRpc\Tests\Response\Json
 */
class SuccessResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonify()
    {
        $expected = json_encode(['jsonrpc' => '2.0', 'result' => 'returned_data', 'id' => 1]);
        $this->assertEquals($expected, (new SuccessResponse(1, 'returned_data'))->jsonify());
    }

}
