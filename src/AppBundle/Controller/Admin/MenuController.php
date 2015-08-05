<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use YPL\WechatSDK\Wechat;
use YPL\WechatSDK\Exception\WechatException;

/**
 * @Route("/admin/wechat")
 */
class MenuController extends CoreController
{
    /**
     * @Route("/menu", name="app_admin_wechat_menu")
     */
    public function indexAction(Request $request)
    {   
        $buttons = array();
        try{
            $wechat = $this->container->get('app_wechat');
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
        return $this->render('AppBundle:Admin\Menu:index.html.twig', array(
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'buttons' => $buttons,
        ));
        
    }

}
