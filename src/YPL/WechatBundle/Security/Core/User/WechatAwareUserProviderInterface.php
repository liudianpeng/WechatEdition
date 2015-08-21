<?php

namespace YPL\WechatBundle\Security\Core\User;
use YPL\WechatSDK\Model\MessageInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 */
interface OAuthAwareUserProviderInterface
{
    /**
     * 通过微信服务器发送的消息，加载用户
     *
     * @param MessageInterface $message
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByWechatMessage(MessageInterface $message);
}