# JSON-RPC

Server side implementation of the JSON-RPC 2.0 protocol in PHP.

[![Build Status](https://travis-ci.org/Kilte/json-rpc.svg?branch=master)](https://travis-ci.org/Kilte/json-rpc)

## Requirements

- PHP &gt;= 5.4

## Usage

```php
use Acme\UserApplication;
use Kilte\JsonRpc\Application;
use Kilte\JsonRpc\Server;
use Kilte\JsonRpc\Request\IOStreamFactory;
use Kilte\JsonRpc\Response\HttpResponse;

$server = new Server(new Application(new UserApplication()), new IOStreamFactory(), new HttpResponse());
$server->handle();
```

### Application

You need to create an instance of `Kilte\JsonRpc\Application` to tell the server about methods that it can call.

Constructor takes a only one argument, which can be an object of some class, or an array of anonymous functions:

```php
use Kilte\JsonRpc\Application;

class UserApplication
{

    public function greet($name)
    {
        return sprintf('Hello %s!', $name);
    }

}
$app = new Application(new UserApplication());

$userApp1 = [];
$userApp1['greet'] = function ($name) {
    return sprintf('Hello %s!', $name);
};
$app1 = new Application($userApp1);
```

### RequestFactory

RequestFactory is responsible for creating an instance of the request, 
based on data received from a client for subsequent processing.

The library provides only `IOStreamFactory`, which receives data from the `php://input` stream.

You can define your own factory by creating a class that inherits from `Kilte\JsonRpc\Request\AbstractFactory`:

```php
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
```

### ResponseInterface

To allow the server to respond to a request, 
you need to pass into the constructor of the server a class that implements `Kilte\JsonRpc\Response\ResponseInterface`.

The library provides only `HttpResponse`. But you can also define your own class:

```php
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
```

## Contributing

- Fork it
- Create your feature branch (git checkout -b awesome-feature)
- Make your changes
- Write/update tests, if necessary
- Update README.md, if necessary
- Push your branch to origin (git push origin awesome-feature)
- Send pull request
- ???
- PROFIT\!\!\!

Do not forget merge upstream changes:

    git remote add upstream https://github.com/Kilte/json-rpc
    git checkout master
    git pull upstream
    git push origin master

Now you can to remove your branch:

    git branch -d awesome-feature
    git push origin :awesome-feature

## LICENSE

THE MIT LICENSE (MIT)