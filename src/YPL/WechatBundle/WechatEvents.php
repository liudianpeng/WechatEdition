<?php

/*
 * This file is part of the WechatEdition package.
 *
 * (c) yplam <yplam@yplam.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YPL\WechatBundle;


class WechatEvents
{
    const MESSAGE = 'wechat_message';

    const TEXT = 'wechat_text';
    const IMAGE = 'wechat_image';
    const LINK = 'wechat_link';
    const VOICE = 'wechat_voice';
    const VIDEO = 'wechat_video';
    const SHORTVIDEO = 'wechat_shortvideo';
    const LOCATION = 'wechat_location';

    const MESSAGE_EVENT = 'wechat_event';

    const EVENT_SUBSCRIBE = 'wechat_event_subscribe';
    const EVENT_UNSUBSCRIBE = 'wechat_event_unsubscribe';
    const EVENT_SCAN = 'wechat_event_scan';
    const EVENT_LOCATION = 'wechat_event_location';
    const EVENT_CLICK = 'wechat_event_click';
    const EVENT_VIEW = 'wechat_event_view';
}

