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
        try {
            $request = $this->requestFactory->forge();
        } catch (JsonRpcException $e) {
            $response = new ErrorResponse(null, $e);
        }
        if (isset($request)) {
            try {
                try {
                    $result = call_user_func_array([$this->app, $request->getMethod()], $request->getParams());
                    if ($request->getId() !== false) {
                        $response = new SuccessResponse($request->getId(), $result);
                    }
                } catch (\BadMethodCallException $e) {
                    throw new MethodNotFoundException($e->getMessage());
                } catch (JsonRpcException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    throw new InternalException($e->getMessage());
                }
            } catch (JsonRpcException $e) {
                $response = new ErrorResponse($request->getId(), $e);
            }
        }
        if (isset($response)) {
            $this->responseFactory->create($this->responseType, [$response])->send();
        }
    }

}
