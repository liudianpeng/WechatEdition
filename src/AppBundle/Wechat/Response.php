<?php
/*
 * This file is part of the WechatEdition package.
 *
 * (c) yplam <yplam@yplam.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace AppBundle\Wechat;

use Symfony\Component\HttpFoundation\Response as HttpResponse;
use YPL\WechatSDK\Model\ResponseInterface;
use YPL\WechatSDK\Wechat;


class Response extends HttpResponse
{

    protected $responseMessage;

    protected $wechat = null;

    public function setWechat(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }

    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    public function hasResponseMessage()
    {
        return !empty($this->responseMessage);
    }

    public function setResponseMessage(ResponseInterface $responseMessage){
        $this->responseMessage = $responseMessage;
    }

    public function sendContent()
    {
        if($this->wechat && $this->hasResponseMessage()){
            echo $this->wechat->response($this->getResponseMessage()->getRawResponse());
        }
        else{
            echo $this->content;
        }

        return $this;
    }

    public function getContent()
    {
        if($this->wechat && $this->hasResponseMessage()){
            return $this->wechat->response($this->getResponseMessage()->getRawResponse());
        }
        else{
            return $this->content;
        }
    }

    public function __toString()
    {
        if($this->wechat && $this->hasResponseMessage()){
            return
                sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText)."\r\n".
                $this->headers."\r\n".
                $this->wechat->response($this->getResponseMessage()->getRawResponse());
        }
        return parent::__toString();
    }
}
    