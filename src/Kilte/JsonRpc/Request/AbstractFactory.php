<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Request;

use Kilte\JsonRpc\Exception\InvalidRequestException;
use Kilte\JsonRpc\Exception\ParseException;

/**
 * Class AbstractFactory
 *
 * Requests factory
 *
 * @package Kilte\JsonRpc\Request
 */
abstract class AbstractFactory
{

    /**
     * Retrieves a request data and returns JSON
     *
     * @return string
     */
    abstract protected function getRequest();

    /**
     * Validates a request data
     *
     * @param array $request Request data
     *
     * @return boolean
     */
    private function validateRequest(array $request)
    {
        $isValid = true;
        foreach (['jsonrpc', 'method'] as $required) {
            if (!array_key_exists($required, $request)) {
                $isValid = false;
            }
        }
        if (array_key_exists('params', $request) && !is_array($request['params'])) {
            $isValid = false;
        }
        if (isset($request['jsonrpc']) && $request['jsonrpc'] !== Request::JSONRPC) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Returns a request instance
     *
     * @throws ParseException
     * @throws InvalidRequestException
     * @return Request
     */
    public function forge()
    {
        $request = json_decode($this->getRequest(), true);
        if (!is_array($request)) {
            throw new ParseException();
        }
        if (!$this->validateRequest($request)) {
            throw new InvalidRequestException();
        }
        foreach (['id' => false, 'params' => []] as $notRequired => $value) {
            $request[$notRequired] = array_key_exists($notRequired, $request) ? $request[$notRequired] : $value;
        }

        return new Request($request['method'], $request['params'], $request['id']);
    }

}
