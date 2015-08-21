<?php

namespace AppBundle\Wechat\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use AppBundle\Wechat\WechatEvents;
use AppBundle\Wechat\Event\MessageEvent;
use FOS\UserBundle\Model\UserManagerInterface;

use YPL\WechatSDK\Model\Response\TextResponse;

class SubscribeSubscriber implements EventSubscriberInterface
{
    private $um;
    private $wechat;

    public function __construct(UserManagerInterface $um, $wechat)
    {
        $this->um = $um;
        $this->wechat = $wechat;
    }
    static public function getSubscribedEvents()
    {
        return array(
            WechatEvents::EVENT_SUBSCRIBE => 'onSubscribe',
            WechatEvents::EVENT_UNSUBSCRIBE => 'onUnsubscribe',
        );
    }

    public function onSubscribe(MessageEvent $event)
    {
        $message = $event->getMessage();
        $wechatId = $message->getFromUserName();
        $user = $this->um->findUserBy(array('wechatId'=>$wechatId));
        $result = $this->wechat->getUserInfo($wechatId);
        if(isset($result['errcode'])){ // 出错，选择不创建用户
            return;
        }
        if($user){
            $user->setWechatStatus(true);
        }
        else{
            $user = $this->um->createUser();
            $username = $result['nickname'];
            $email = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36).'@'.$_SERVER['HTTP_HOST'];

            while($this->um->findUserByUsername($username)){
                $username .= mt_rand(1,99);
            }
            // 随机邮箱
            while($this->um->findUserByEmail($email)){
                $email = mt_rand(1,99).$email;
            }
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setEnabled(true);
            $user->setPicture($result['headimgurl']);
            $user->setWechatStatus(true);
            $user->setWechatAt(new \DateTime('now'));
            $user->setWechatId($wechatId);
            $user->setPlainPassword(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        }
        
        $this->um->updateUser($user);

        // 发一条默认消息
        $event->getResponse()->setResponseMessage(new TextResponse(array(
            'ToUserName' => $message->getFromUserName(),
            'FromUserName' => $message->getToUserName(),
            'Content' => "Hi {$result['nickname']}, 欢迎关注我们的微信账号。想获取更多信息，请登录我们的网站。",
        )));

    }

    public function onUnsubscribe(MessageEvent $event)
    {
        
        $wechatId = $event->getMessage()->getFromUserName();
        $user = $this->um->findUserBy(array('wechatId'=>$wechatId));
        if($user){
            $user->setWechatStatus(false);
            $this->um->updateUser($user);
        }
    }

}