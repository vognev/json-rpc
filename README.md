# JSON-RPC

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

## TODO

- Documentation

## LICENSE

THE MIT LICENSE (MIT)