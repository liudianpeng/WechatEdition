<?php

namespace YPL\WechatBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use YPL\WechatSDK\Wechat;
use YPL\WechatSDK\Exception\WechatException;

class MenuController extends Controller
{
    public function indexAction(Request $request)
    {   
        $buttons = array();
        try{
            $wechat = $this->container->get('ypl_wechat');
            if($request->getMethod() == 'POST'){
                $re = array();
                $re['status'] = false;
                $menuStr = $request->request->get('menu');
                $result = $wechat->setMenu($menuStr);
                if($result && $result['errcode'] == 0){
                    $re['status'] = true;
                }
                else{
                    $re['message'] = $result['errcode'] . ' ' . $result['errmsg'];
                }
                return new JsonResponse($re);
            }
            
            $content = $wechat->getMenu();
            if(empty($content)){
                throw new WechatException('API接口无法接收数据');
            }
            if(isset($content['errcode'])){
                throw new WechatException($content['errmsg'], $content['errcode']);
            }
            if(!isset($content['menu']['button'])){
                throw new WechatException('获取菜单失败');
            }
            $buttons = $content['menu']['button'];
        }
        catch(WechatException $e){
            $request->getSession()->getFlashBag()->add('error', $e->getErrorCode() . ': ' .$e->getMessage());
        }
        return $this->render('YPLWechatBundle:Admin\Menu:index.html.twig', array(
            'buttons' => $buttons,
        ));
        
    }

}
