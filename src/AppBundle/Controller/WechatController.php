<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use YPL\WechatSDK\Exception\WechatException;
use YPL\WechatSDK\Model\Message\BaseMessage;
use YPL\WechatSDK\Model\Message\BaseEvent;

use AppBundle\Wechat\Response;
use AppBundle\Wechat\WechatEvents;
use AppBundle\Wechat\Event\MessageEvent;


class WechatController extends Controller
{
    /**
     * @Route("/wechat", name="wechat")
     */
    public function wechatAction(Request $request)
    {
        $wechat = $this->get('app_wechat');
        $response = new Response('success');
        $response->setWechat($wechat);
        try{
            $rawMessage = $wechat->getRawMessage();
            if($echostr = $request->query->get('echostr')){
                $response->setContent($echostr);
                return $response;
            }
            $messageManager = $this->get('app_wechat_message_manager');
            $message = $messageManager->createFromRawMessage($rawMessage);
            if(!($message && $messageManager->validate($message))){
                throw new WechatException('message validate error');
            }
            $event = new MessageEvent($message, $response);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(WechatEvents::MESSAGE, $event);
            if($message instanceof BaseMessage){
                $dispatcher->dispatch('wechat_'.$message->getMsgType(), $event);
            }
            else if($message instanceof BaseEvent){
                $dispatcher->dispatch('wechat_event_'.strtolower($message->getEvent()), $event);
            }
        }
        catch(WechatException $e){
            $response->setContent('error');
            $this->get('logger')->error('Wechat - ' . $e->getMessage());
        }
        return $response;
    }
}
