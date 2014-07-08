<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests\Request;

use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Request\Request;

/**
 * Class IOStreamFactoryTest
 *
 * @package Kilte\JsonRpc\Tests\Request
 */
class IOStreamFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testGetRequest()
    {
        $factory = new IOStreamFactory();
        $factory->setStream(__DIR__ . '/../Fixtures/stream.json');
        $this->assertEquals($factory->forge(), new Request('method', ['arg'], 'id'));
    }

}
