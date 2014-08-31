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

namespace Kilte\JsonRpc\Response;

/**
 * Class HttpResponse
 *
 * @package Kilte\JsonRpc\Response
 */
class HttpResponse
{

    /**
     * @var string Content
     */
    private $content;

    /**
     * Constructor
     *
     * @param string $content Content
     *
     * @return self
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Returns content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sends response
     *
     * @return void
     */
    public function send()
    {
        header('Content-Type: application/json', true);
        echo $this->content;
    }

}
