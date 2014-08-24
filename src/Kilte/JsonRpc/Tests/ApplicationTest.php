<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Tests;

use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Tests\Fixtures\UserApplication;

/**
 * Class ApplicationTest
 *
 * @package Kilte\JsonRpc\Tests
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Returns application instance
     *
     * @return object
     */
    private function getApp()
    {
        return new Application(
            [
                'greet' => function ($name) {
                    return sprintf('hello, %s', $name);
                }
            ]
        );
    }

    public function testConstructor()
    {
        new Application(
            [
                function () {
                }
            ]
        );
        new Application(new \StdClass());
        $this->setExpectedException('\\InvalidArgumentException', 'Expects array or object, string given');
        new Application('string');
    }

    public function testConstructorArrayFailed()
    {
        $this->setExpectedException('\\InvalidArgumentException', 'Item "0" is not callable');
        new Application(['not_callable']);
    }

    public function testCall()
    {
        $app = $this->getApp();
        $this->assertEquals($app->greet('kilte'), 'hello, kilte');
        $this->setExpectedException('\\BadMethodCallException', 'Method "non_exists" does not exists');
        $app->non_exists();
    }

    public function testCallInvalidParams()
    {
        $this->setExpectedException(
            '\\Kilte\\JsonRpc\\Exception\\InvalidParamsException',
            'Invalid method parameter(s)',
            -32602
        );
        $this->getApp()->greet();
    }

    public function testCallFromObject()
    {
        /** @var $app object */
        $app = new Application(new UserApplication());
        $this->assertEquals($app->greet('kilte'), 'hello, kilte');
    }

    public function testCallNamespace()
    {
        $app = new Application(['namespace' => new UserApplication()]);
        $this->assertEquals(call_user_func([$app, 'namespace.greet'], 'kilte'), 'hello, kilte');
    }

}
