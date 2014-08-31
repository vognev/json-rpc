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

namespace Kilte\JsonRpc\Tests\Request;

use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Request\Request;

/**
 * IOStreamFactory Test
 *
 * @package Kilte\JsonRpc\Tests\Request
 */
class IOStreamFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testGetRequest()
    {
        $factory = new IOStreamFactory();
        $factory->setStream(__DIR__ . '/../Fixtures/stream.json');
        $this->assertEquals($factory->forge(), [[new Request('method', ['arg'], 'id')], false]);
    }

}
