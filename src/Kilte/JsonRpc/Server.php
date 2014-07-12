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
use Kilte\JsonRpc\Response\ResponseFactory;

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
     * @var ResponseFactory Response factory
     */
    private $responseFactory;

    /**
     * @var string Response type
     */
    private $responseType;

    /**
     * Constructor
     *
     * @param Application     $app             An application instance
     * @param AbstractFactory $requestFactory  Request factory
     * @param ResponseFactory $responseFactory Response factory
     * @param string          $responseType    Type of response
     *
     * @return self
     */
    public function __construct(
        Application $app,
        AbstractFactory $requestFactory,
        ResponseFactory $responseFactory,
        $responseType
    ) {
        $this->app = $app;
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
        $this->responseType = $responseType;
    }

    /**
     * Handles a request and sends a response
     *
     * @return void
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
                    try {
                        try {
                            $result = call_user_func_array([$this->app, $request->getMethod()], $request->getParams());
                            if ($request->getId() !== false) {
                                $responses[] = new SuccessResponse($request->getId(), $result);
                            }
                        } catch (\BadMethodCallException $e) {
                            throw new MethodNotFoundException($e->getMessage());
                        } catch (JsonRpcException $e) {
                            throw $e;
                        } catch (\Exception $e) {
                            throw new InternalException($e->getMessage());
                        }
                    } catch (JsonRpcException $e) {
                        $responses[] = new ErrorResponse($request->getId(), $e);
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
            $this->responseFactory->create($this->responseType)->send($output);
        }
    }

}
