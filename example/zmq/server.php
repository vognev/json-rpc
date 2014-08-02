<?php
use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Request\AbstractFactory;
use Kilte\JsonRpc\Server;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Class JsonRpcApplication
 */
class JsonRpcApplication
{

    /**
     * @param $name
     * @return string
     */
    public function greet($name)
    {
        return sprintf('Hello, %s', $name);
    }

}

/**
 * Class ZMQRequestFactory
 */
class ZMQRequestFactory extends AbstractFactory
{

    private $responder;

    /**
     * @param ZMQSocket $responder
     */
    public function __construct(ZMQSocket $responder)
    {
        $this->responder = $responder;
    }

    protected function getRequest()
    {
        $request = $this->responder->recv();
        printf("Received message: %s\n", $request);

        return $request;
    }

}

/**
 * Class ZMQResponse
 */
class ZMQResponse
{

    private $responder;

    /**
     * @param ZMQSocket $responder
     */
    public function __construct(ZMQSocket $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @param $output
     */
    public function send($output)
    {
        $this->responder->send($output);
    }

}

$context = new ZMQContext();
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind("tcp://127.0.0.1:5555");
$response = new ZMQResponse($responder);

$server = new Server(
    new Application(new JsonRpcApplication()),
    new ZMQRequestFactory($responder)
);

while (true) {
    $response->send($server->handle());
}
