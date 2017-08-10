<?php
namespace app\weixin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;

class Index extends Controller
{
    private $weixinObj = null;

    protected function _initialize(){
        $this->weixinObj = new_weixin_class();//实例化微信SDK-类
    }


    //接受响应微信服务器发来的消息--首页
    public function index()
    {
        //@file_put_contents(ROOT_PATH.'public/log/text.txt',var_export($_GET,true),FILE_APPEND);
        //$this->weixinObj->valid();

        $type = $this->weixinObj->getRev()->getRevType();

        switch($type)
        {
            //文本消息
            case $type=='text':
                $text = $this->weixinObj->getRev()->getRevContent();
                $this->weixinObj->text($text)->reply();
                break;
            //语音消息
            case $type=='voice':


                break;
            //图片消息
            case $type=='image':

                break;
            //视频消息
            case $type=='video':

                break;
            //短视频消息
            case $type=='shortvideo':

                break;
            //发送位置消息
            case $type=='location':
                $weizhi = $this->weixinObj->getRevGeo();
                //@file_put_contents(ROOT_PATH.'public/log/weixin_event.txt',var_export($weizhi,true),FILE_APPEND);
                $this->weixinObj->text(
                    '经度：'.$weizhi['x']."\r\n".
                    '维度：'.$weizhi['y']."\r\n".
                    '位置：'.$weizhi['label']
                )->reply();
                break;
            //事件处理
            default:
                $event = $this->weixinObj->getRevEvent();
                $openid = $this->weixinObj->getRevFrom();
                $getRevEventGeo = $this->weixinObj->getRevEventGeo();//地理位置信息
                //@file_put_contents(ROOT_PATH.'public/log/wx_info.txt',var_export($event,true)."\r\n",FILE_APPEND);
                //关注事件
                if($event['event'] == 'subscribe'){
                    $this->weixinObj->text('欢迎关注七里香的公众号，官方网站<a href="www.iouhai.top">www.iouhai.top</a>')->reply();

                    if(strpos($event['key'],'qrscene_') !== false)
                    {
                        $uid = str_replace('qrscene_', '', $event['key']);
                        $this->update_userweixin_info($openid,$uid);
                    }
                }
                //取消关注事件
                elseif($event['event'] == 'unsubscribe'){
                    Db::name('user')->where(array('weixin_openid'=>$openid))->update(array('wx_is_cancel'=>1,'weixin_openid'=>'','weixin_unionid'=>''));
                }
                //已经关注扫码或者进入公众号
                elseif($event['event'] == 'SCAN'){
                    $uid = $event['key'];
                    $this->update_userweixin_info($openid,$uid);
                }
                //上报地理位置
                elseif($event['event'] == 'LOCATION'){
                    $weizhi_sendtime = cache('weizhi_sendtime');
                    if(!empty($weizhi_sendtime) && $weizhi_sendtime > time()){//没隔30分钟 才发送一次位置
                        return false;
                    }
                    $weizhi = $this->weixinObj->getRevEventGeo();

                    $result = @file_get_contents("http://restapi.amap.com/v3/geocode/regeo?output=json&location={$weizhi['y']},{$weizhi['x']}&key=6bc8cc66762a510a9a9e7ed99ff76658&radius=500&extensions=all");
                    $result = json_decode($result,true);
                    //@file_put_contents(ROOT_PATH.'public/log/weixin_event.txt',var_export(array($result,$weizhi),true)."\r\n",FILE_APPEND);
                    $reply_str = '' ;
                    $fujin_area_count = count($result['regeocode']['pois']);
                    for($i=0;$i<intval($fujin_area_count/3);$i++){
                        $reply_str .= '服务名称：'.$result['regeocode']['pois'][$i]['name']."\r\n" ;
                        $reply_str .= '服务类型：'.$result['regeocode']['pois'][$i]['type']."\r\n" ;
                        $reply_str .= '经度：'.$result['regeocode']['pois'][$i]['location']."\r\n" ;
                        $reply_str .= '维度：'.$result['regeocode']['pois'][$i]['distance']."\r\n" ;
                        $reply_str .= '区域：'.$result['regeocode']['pois'][$i]['businessarea']."\r\n";
                    }

                     $this->weixinObj->text(
                        '您的位置：'.$result['regeocode']['formatted_address']."  附近。。\r\n".
                        '经度：'.$weizhi['x']."\r\n".
                        '维度：'.$weizhi['y']."\r\n".
                        '精确度：'.$weizhi['precision']."\r\n".
                        '您附近还有这些地方：'."\r\n".$reply_str
                    )->reply();
                    cache('weizhi_sendtime',time()+30*60);
                }
                else
                {
                    //@file_put_contents(ROOT_PATH.'public/log/weixin_event.txt',$event['event']."\r\n",FILE_APPEND);
                }
        }

    }

    public function t()
    {

        p($this->weixinObj);
        //p(cache('dingwei'));

    }

    //更新用户的微信绑定情况
    public function update_userweixin_info($openid,$uid)
    {
        $user_info = Db::name('user')->where(array('uid'=>$uid))->find();
        //@file_put_contents(ROOT_PATH.'public/log/weixin_event.txt',var_export(array($user_info,'获取用户信息'),true)."\r\n",FILE_APPEND);
        if(!$user_info['weixin_openid'] or !$user_info['weixin_unionid'])
        {
            $wx_info = $this->weixinObj->getUserInfo($openid);
            $data = array(
                'weixin_openid'  => $openid,
                'weixin_unionid' => isset($wx_info['unionid'])?$wx_info['unionid']:'',
                'wx_is_cancel'   => 0,
            );
            Db::name('user')->where(array('uid'=>$uid))->update($data);
        }

        return true;
    }

    //生成微信带参数的二维码
    public function weixin_erweima($uid='')
    {
        if(!$uid){
            $userinfo = get_user_info();//实例化微信SDK-类
            $uid = $userinfo['uid'];
        }else{
            $userinfo = Db::name('user')->where(array('uid'=>$uid))->find();
            if(!$userinfo) return false;
            $uid = $userinfo['uid'];
        }

        $getQRCode = $this->weixinObj->getQRCode($uid);//参数1：二维码携带参数=====参数2：临时或者永久二维码
        $ticket = $getQRCode['ticket'];
        $code_url = $this->weixinObj->getQRUrl($ticket);
        echo @file_get_contents($code_url);
    }




}