<?php

namespace YPL\WechatBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use YPL\WechatSDK\Wechat;
use YPL\WechatSDK\Exception\WechatException;


class AdminController extends Controller
{
    public function dashboardAction(Request $request)
    {   
        return $this->render('YPLWechatBundle:Admin:dashboard.html.twig', array(

        ));
        
    }

    public function sidebarAction(Request $request)
    {
        return $this->render('YPLWechatBundle:Admin:sidebar.html.twig', array(

        ));
    }

}
