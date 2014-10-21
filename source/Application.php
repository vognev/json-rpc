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

namespace Kilte\JsonRpc;

use Kilte\JsonRpc\Exception\InvalidParamsException;

/**
 * Class Application
 *
 * Application wrapper
 *
 * @package Kilte\JsonRpc
 */
class Application
{

    /**
     * @var array|object Application
     */
    protected $app;

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
                if (!is_callable($item) && !is_object($item)) {
                    throw new \InvalidArgumentException(sprintf('Item "%s" is not callable or object', $key));
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
    protected function reflectionFactory($method)
    {
        if (is_array($method) && sizeof($method) == 2) {
            $r = new \ReflectionMethod(get_class($method[0]), $method[1]);
        } else {
            $r = new \ReflectionFunction($method);
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
        if (is_array($this->app)) {
            try {
                list($namespace, $methodName) = $this->parseName($name);
                if (array_key_exists($namespace, $this->app)) {
                    $namespace = $this->app[$namespace];
                    if (is_object($namespace) && method_exists($namespace, $methodName)) {
                        $method = [$namespace, $methodName];
                    }
                }
            } catch (\InvalidArgumentException $exception) {
                if (array_key_exists($name, $this->app)) {
                    $method = $this->app[$name];
                }
            }
        } elseif (method_exists($this->app, $name)) {
            $method = [$this->app, $name];
        }
        if (!isset($method)) {
            throw new \BadMethodCallException(sprintf('Method "%s" does not exists', $name));
        }
        $r = $this->reflectionFactory($method);

        if ($total < $r->getNumberOfRequiredParameters() || $total > $r->getNumberOfParameters()) {
            throw new InvalidParamsException();
        }

        // need higher abstraction here
        // but I hope there is no way to pass stdClass
        // using client lib.

        $undefined      = new \stdClass();
        $calleeParams   = array();

        foreach($r->getParameters() as $parameter) {
            if ($parameter->isOptional()) {
                $calleeParams[$parameter->getName()]
                    = $parameter->getDefaultValue();
            } else {
                $calleeParams[$parameter->getName()] = $undefined;
            }
        }

        foreach($args as $argkey => $arg) {
            if (array_key_exists($argkey, $calleeParams)) {
                $calleeParams[$argkey] = $arg;
            } elseif (is_numeric($argkey)) {
                $argkey = (int) $argkey;
                $arguments = array_keys($calleeParams);
                if (array_key_exists($argkey, $arguments)) {
                    $calleeParams[$arguments[$argkey]] = $arg;
                } else {
                    throw new InvalidParamsException();
                }
            } else {
                throw new InvalidParamsException();
            }
        }

        foreach($calleeParams as &$param)
            if ($param === $undefined)
                throw new InvalidParamsException();

        return call_user_func_array($method, $calleeParams);
    }

    /**
     * Returns namespace and method
     *
     * @param string $name Name
     *
     * @return array [namespace, method]
     *
     * @throws \InvalidArgumentException
     */
    protected function parseName($name)
    {
        $name = explode('.', $name);
        if (sizeof($name) < 2) {
            throw new \InvalidArgumentException('Unexpected name given');
        }
        $namespace = array_shift($name);
        $name = array_map('ucwords', $name);
        $name[0] = strtolower($name[0]);
        $method = implode('', $name);

        return [$namespace, $method];
    }

}
