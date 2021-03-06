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

use Kilte\JsonRpc\Exception\InvalidRequestException;
use Kilte\JsonRpc\Request\Request;

/**
 * AbstractFactory Test
 *
 * @package Kilte\JsonRpc\Tests\Request
 */
class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns abstract factory instance
     *
     * @param mixed $returnValue Request
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Kilte\JsonRpc\Request\AbstractFactory
     */
    private function getFactory($returnValue)
    {
        $factory = $this->getMockForAbstractClass('\\Kilte\\JsonRpc\\Request\\AbstractFactory');
        $factory->expects($this->any())->method('getRequest')->will(
            $this->returnValue($returnValue)
        );

        return $factory;
    }

    public function testForgeSuccess()
    {
        $request = new Request('method', ['param'], 'id');
        $factory = $this->getFactory((string) $request);
        $this->assertEquals($factory->forge(), [[$request], false]);
    }

    public function testForgeFailedWithParseException()
    {
        $factory = $this->getFactory('invalid_json');
        $this->setExpectedException(
            '\\Kilte\\JsonRpc\\Exception\\ParseException',
            'An error occurred on the server while parsing the JSON text.',
            -32700
        );
        $factory->forge();
    }

    public function testForgeFailedWithInvalidRequestException()
    {
        $factory = $this->getFactory(json_encode(['params' => 'could not be string', 'jsonrpc' => 'invalid_version']));
        $this->assertEquals([[new InvalidRequestException()], false], $factory->forge());
    }

}
