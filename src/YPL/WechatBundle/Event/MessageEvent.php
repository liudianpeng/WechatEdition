<?php

namespace YPL\WechatBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use YPL\WechatBundle\Response;

class MessageEvent extends Event
{
    private $message;
    private $response;

    public function __construct($message, Response $response)
    {
        $this->message = $message;
        $this->response = $response;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}