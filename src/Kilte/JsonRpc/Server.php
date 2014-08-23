<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc;

use Kilte\JsonRpc\Exception\InternalException;
use Kilte\JsonRpc\Exception\JsonRpcException;
use Kilte\JsonRpc\Exception\MethodNotFoundException;
use Kilte\JsonRpc\Request\AbstractFactory;
use Kilte\JsonRpc\Request\Request;
use Kilte\JsonRpc\Response\Json\AbstractResponse;
use Kilte\JsonRpc\Response\Json\ErrorResponse;
use Kilte\JsonRpc\Response\Json\SuccessResponse;

/**
 * Class Server
 *
 * @package Kilte\JsonRpc
 */
class Server
{

    /**
     * @var Application An application instance
     */
    private $app;

    /**
     * @var AbstractFactory Request factory
     */
    private $requestFactory;

    /**
     * Constructor
     *
     * @param Application     $app            An application instance
     * @param AbstractFactory $requestFactory Request factory
     *
     * @return self
     */
    public function __construct(Application $app, AbstractFactory $requestFactory)
    {
        $this->app = $app;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Handles a request and returns a response
     *
     * @return string|null
     */
    public function handle()
    {
        /** @var $responses AbstractResponse[] */
        $responses = [];
        try {
            $result = $this->requestFactory->forge();
            $requests = $result[0];
            $isBatch = $result[1];
        } catch (JsonRpcException $e) {
            $responses[] = new ErrorResponse(null, $e);
            $isBatch = false;
        }
        if (isset($requests)) {
            foreach ($requests as $request) {
                if ($request instanceof Request) {
                    $error = null;
                    try {
                        $result = call_user_func_array([$this->app, $request->getMethod()], $request->getParams());
                        if ($request->getId() !== false) {
                            $responses[] = new SuccessResponse($request->getId(), $result);
                        }
                    } catch (\BadMethodCallException $e) {
                        $error = new MethodNotFoundException($e->getMessage());
                    } catch (JsonRpcException $e) {
                        $error = $e;
                    } catch (\Exception $e) {
                        $error = new InternalException($e->getMessage());
                    }
                    if ($error !== null) {
                        $responses[] = new ErrorResponse($request->getId(), $error);
                    }
                } else {
                    $responses[] = new ErrorResponse(null, $request);
                }
            }
        }
        if (!empty($responses) && isset($isBatch)) {
            if (sizeof($responses) == 1 && $isBatch === false) {
                $output = $responses[0]->jsonify();
            } else {
                $responses = array_map(
                    function (AbstractResponse $response) {
                        return $response->jsonify();
                    },
                    $responses
                );
                $output = sprintf('[%s]', implode(',', $responses));
            }
        }

        return isset($output) ? $output : null;
    }

}
