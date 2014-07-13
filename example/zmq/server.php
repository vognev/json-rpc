<?php
use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Request\AbstractFactory;
use Kilte\JsonRpc\Response\ResponseInterface;
use Kilte\JsonRpc\Server;

require __DIR__ . '/../../vendor/autoload.php';

class JsonRpcApplication
{

    public function greet($name)
    {
        return sprintf('Hello, %s', $name);
    }

}

class ZMQRequestFactory extends AbstractFactory
{

    private $responder;

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

class ZMQResponse implements ResponseInterface
{

    private $responder;

    public function __construct(ZMQSocket $responder)
    {
        $this->responder = $responder;
    }

    public function send($output)
    {
        $this->responder->send($output);
    }

}

$context = new ZMQContext();
$responder = new ZMQSocket($context, ZMQ::SOCKET_REP);
$responder->bind("tcp://127.0.0.1:5555");

$server = new Server(
    new Application(new JsonRpcApplication()),
    new ZMQRequestFactory($responder),
    new ZMQResponse($responder)
);

while (true) {
    try {
        $server->handle();
    } catch (ZMQSocketException $e) {
        // Notification received
        $responder->send('');
    }
}
