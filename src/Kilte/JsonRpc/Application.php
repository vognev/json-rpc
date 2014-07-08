<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc;

use Kilte\JsonRpc\Exception\InvalidParamsException;

/**
 * Class Application
 *
 * Application wrapper
 *
 * @package Kilte\JsonRpc
 */
class Application extends \ArrayObject
{

    /**
     * @var array|object Application
     */
    private $app;

    /**
     * Constructor
     *
     * @param array|object $app Application
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function __construct($app)
    {
        if (!is_object($app) && !is_array($app)) {
            throw new \InvalidArgumentException(sprintf('Expects array or object, %s given', gettype($app)));
        }
        if (is_array($app)) {
            foreach ($app as $key => $item) {
                if (!is_callable($item)) {
                    throw new \InvalidArgumentException(sprintf('Item "%s" is not callable', $key));
                }
            }
        }
        $this->app = $app;
    }

    /**
     * Returns reflection for method
     *
     * Returns false, if given method is not callable
     *
     * @param callable $method Method to reflect
     *
     * @return boolean|\ReflectionFunctionAbstract
     */
    private function reflectionFactory($method)
    {
        if ($method instanceof \Closure) {
            $r = new \ReflectionFunction($method);
        } elseif (is_array($method) && sizeof($method) == 2) {
            $r = new \ReflectionMethod(get_class($method[0]), $method[1]);
        } else {
            $r = false;
        }

        return $r;
    }

    /**
     * This method is triggered when invoking inaccessible methods in an object context
     *
     * @param string $name Method name
     * @param array  $args Arguments
     *
     * @throws \BadMethodCallException
     * @throws InvalidParamsException
     *
     * @return mixed
     */
    public function __call($name, array $args = [])
    {
        $total = count($args);
        if (is_array($this->app) && array_key_exists($name, $this->app)) {
            $method = $this->app[$name];
        } elseif (method_exists($this->app, $name)) {
            $method = [$this->app, $name];
        } else {
            throw new \BadMethodCallException(sprintf('Method "%s" does not exists', $name));
        }
        $r = $this->reflectionFactory($method);
        if ($r === false) {
            throw new \BadMethodCallException(sprintf('Method %s is not callable', $name));
        }
        if ($total < $r->getNumberOfRequiredParameters() || $total > $r->getNumberOfParameters()) {
            throw new InvalidParamsException();
        }

        return call_user_func_array($method, $args);
    }

}
