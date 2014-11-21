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
     * @param \StdClass $request Request data
     *
     * @return boolean
     */
    private function validateRequest(\StdClass $request)
    {
        $isValid = true;
        foreach (array('jsonrpc', 'method') as $required) {
            if (!isset($request->$required)) {
                $isValid = false;
            }
        }
        if (isset($request->params) && (!is_array($request->params) && !$request->params instanceof \StdClass)) {
            $isValid = false;
        }
        if (isset($request->jsonrpc) && $request->jsonrpc !== Request::JSONRPC) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Returns requests instances
     *
     * @throws ParseException
     * @throws InvalidRequestException
     * @return array
     */
    public function forge()
    {
        $isBatch = false;
        $requests = json_decode($this->getRequest(), true);
        if ($requests instanceof \StdClass) {
            $requests = array($requests);
        } elseif (!is_array($requests)) {
            throw new ParseException();
        } else {
            $isBatch = true;
        }
        if (empty($requests)) {
            throw new InvalidRequestException();
        }
        $result = array();
        foreach ($requests as $request) {
            if (!$request instanceof \StdClass) {
                $result[] = new InvalidRequestException();
            } elseif (!$this->validateRequest($request)) {
                $result[] = new InvalidRequestException();
            } else {
                foreach (array('id' => false, 'params' => array()) as $notRequired => $value) {
                    $request->$notRequired = isset($request->$notRequired) ? $request->$notRequired : $value;
                }
                $result[] = new Request($request->method, (array) $request->params, $request->id);
            }
        }

        return array($result, $isBatch);
    }

}
