<?php

namespace YPL\WechatBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class WechatAdmin extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array('childrenAttributes' => array('class' => 'sidebar-menu')));
        $menu->addChild('<i class="fa fa-th"></i><span>菜单</span>', array(
            'route' => 'ypl_wechat_admin_menu',
            'extras' => array('safe_label' => true)            
            ));

        return $menu;
    }
}