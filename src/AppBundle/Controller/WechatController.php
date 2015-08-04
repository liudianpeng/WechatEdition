<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YPL\WechatSDK\Exception\WechatException;
use Symfony\Component\HttpFoundation\Response;

class WechatController extends Controller
{
    /**
     * @Route("/wechat", name="wechat")
     */
    public function wechatAction(Request $request)
    {
        $wechat = $this->get('app_wechat');
        try{
            $rawMessage = $wechat->getRawMessage();
            if($echostr = $request->query->get('echostr')){
                return new Response($echostr);
            }
            $message = $this->get('app_wechat_message_manager')->createFromRawMessage($rawMessage);
            return new Response('success');
        }
        catch(WechatException $e){
            exit('error');
        }
    }
}
