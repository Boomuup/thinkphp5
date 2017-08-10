<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;

class Weixin extends Common
{
    public $weixinObj = null;


    protected function _initialize(){
        //$this->weixinObj = new_weixin_class();//实例化微信SDK-类

    }

    public function index()
    {
        $weixin = new_weixin_class();//实例化微信SDK-类


        $openid = 'oX7b7waIv8NZmCDLV270NUre8Bzs';
        $data = array(
            'touser'      => $openid,
            'template_id' => '9CAYmbkSKa35J4TusmYSMYPYkPmKMH9-_H5bFTibTLg',
            'topcolor'    => '#7b68ee',
            'data'        => array(
                'title'     => array('value' => '感谢你在本店购买！', 'color' => '#000'),
                'name'      => array('value' => '营养快线', 'color' => '#000'),
                'price'     => array('value' => '5', 'color' => '#0354AF'),
                'time'      => array('value' => date('Y年m月d日 H:i:s'), 'color' => '#000'),
                'remark'    => array('value' => '祝您购物愉快！', 'color' => '#000'),
            ),
        );
        $res = $weixin->sendTemplateMessage($data);
        p($res);
          /**/

    }

    public function t()
    {
        $weixin = new_weixin_class();
        $menu = $weixin->getMenu();

        $menu['menu']['button'][0]['name'] = '七里香';
        $menu['menu']['button'][0]['url']  = 'http://iouhai.top/';
        $menu['menu']['button'][2]['sub_button'][2] = array('type'=>'view','name'=>'欢迎三房东','url'=>'http://www.wengtao.xin/','sub_button'=>array());
        //ps($menu);
        //P($weixin->createMenu($menu['menu']));
            /**/
        $type = $weixin->getRev()->getRevType();
        switch($type) {
            case Wechat::MSGTYPE_TEXT:
                $weixin->text("hello, I'm wechat")->reply();
                exit;
                break;
            case Wechat::MSGTYPE_EVENT:

                break;
            case Wechat::MSGTYPE_IMAGE:

                break;
            default:
                $weixin->text("help info")->reply();
       }

    }

    /**
     * @return array
     */
    public function weixin_login()
    {
        $appid = 'wx06eac275bfb8ed67';
        //$weixin = new_weixin_class();
        //$url = $weixin->getOauthRedirect(url('start/index/weixinCallback'),'666');

        //$this->redirect($url);
        $this->assign('appid',$appid);
        return $this->fetch('weixin_login');
    }

    //微信回调地址
    public function weixinCallback()
    {
        $weixin = new_weixin_class();
        $code = input('get.code/s');
        if($code){
            $user_token = $weixin->getOauthAccessToken() ? $weixin->user_token : '空的user_token';
            ps($user_token);
        }else{
            ps('code不存在！');
        }
    }


}