<?php

/**
 * Part of the JsonRpc
 *
 * @author  Kilte Leichnam <nwotnbm@gmail.com>
 * @package JsonRpc
 */

namespace Kilte\JsonRpc\Request;

/**
 * Class IOStreamFactory
 *
 * Allows to retrieve a request from the I/O stream
 *
 * @package Kilte\JsonRpc\Request
 */
class IOStreamFactory extends AbstractFactory
{

    /**
     * @var string Stream
     */
    private $stream = 'php://input';

    /**
     * Sets new stream
     *
     * @param string $stream Stream
     *
     * @return void
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequest()
    {
        return file_get_contents($this->stream);
    }

}
