<?php


echo 666;die;

header("Content-type: text/html; charset=utf-8");

define('APPID',    'wx06eac275bfb8ed67');
define('APPSECRET','a92941ac72c1a41d203a11b5058a276e');
define('TOKEN','weixin');

session_start();
ini_set('session.gc_maxlifetime', 7000); //设置session过期时间
ini_get('session.gc_maxlifetime');//获取session过期时间

$weixin = new Weixin();

function p($val){
    echo '<pre>';
    print_r($val);
    echo '</pre>';
}


class Weixin
{
    function __construct()
    {
        $this->yanzheng();
        // $this->getAccessToken();
        // //$this->createMenu();//创建自定义菜单
        // $this->share();
        // $this->replyMessage();
    }


    public function yanzheng()
    {
        echo 666;die;
        if($_GET)
        {
            //timestamp  nonce  token按字典排序
            $timestamp = $_GET['timestamp'];
            $nonce     = $_GET['nonce'];
            $token     = 'weixin';
            $signature = $_GET['signature'];
            $echostr   = $_GET['echostr'];

            $array     = array($timestamp,$nonce,$token);
            sort($array);
            //排序后的三个参数用sha1加密
            $timestr = implode('',$array);
            $timestr = sha1($timestr);
            //加密后的的字符串与signature对比，是否来自微信
            if($timestr==$signature)
            {
                echo $echostr;
            }
        }
    }

    //获取access token
    private function getAccessToken(){
        if($_SESSION['access_token']){
            return $_SESSION['access_token'];
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
            $atjson=file_get_contents($url);
            $result=json_decode($atjson,true);//json解析成数组
            if(!isset($result['access_token'])){
                exit( '获取access_token失败！' );
            }else{
                $_SESSION['access_token'] = $result["access_token"];
                $_SESSION['access_token_expire_time']  = time()+7000;
            }
        }     
    }

    /**
     *  创建自定义菜单
     */
    private function createMenu(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$_SESSION['access_token'];

        $data =     '{
                         "button":[
                         {
                               "type":"view",
                               "name":"视频",
                               "url":"http://v.qq.com/"
                         },
                         {
                              "name":"首页",
                              "sub_button":[
                                {
                                   "type":"click",
                                   "name":"欢迎光临",
                                   "key":"e3"
                                },
                                {
                                   "type":"click",
                                   "name":"你好！",
                                   "key":"e3"
                                },
                                {
                                   "type":"click",
                                   "name":"请问你要吃什么？",
                                   "key":"e3"
                                },
                                {
                                   "type":"click",
                                   "name":"一份白切鸡",
                                   "key":"e3"
                                },
                                {
                                   "type":"click",
                                   "name":"谢谢！",
                                   "key":"e3"
                                }
                              ]
                         },
                         {
                               "name":"威武的房东",
                               "sub_button":[
                                    {
                                       "type":"click",
                                       "name":"欢迎大房东",
                                       "key":"e3"
                                    },
                                    {
                                       "type":"click",
                                       "name":"欢迎二房东",
                                       "key":"e3"
                                    },
                                    {
                                       "type":"click",
                                       "name":"欢迎三房东",
                                       "key":"e3"
                                    },
                                    {
                                       "type":"click",
                                       "name":"欢迎八房东",
                                       "key":"e3"
                                    },
                                ]
                         }
                       ]
                    }';
        echo $this->use_curl($url,$data);
    }

    /**
     *  删除自定义菜单
     */
    private function deleteMenu(){

        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$_SESSION['access_token'];

        echo $this->use_curl($url);
    }

    public function replyMessage()
    {

        $postxml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $postObj = simplexml_load_string($postxml);

        $temple     = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                            </xml>";
        $touser     = $postObj->FromUserName;
        $fromuser   = $postObj->ToUserName;
        $time       = time();
        $msgType    = 'text';

        //回复  事件推送
        if( strtolower($postObj->MsgType) == 'event' )
        {
            switch(strtolower($postObj->MsgType) == 'event')
            {
                case $postObj->Event == 'subscribe':
                    //$conText    = '欢迎来到英特尔中国 !'."\n".'Welcome to intel China！';
                    $array = array(
                        array(
                            'title'       => '至强Xeon E7 荣耀登场 王者霸气不可阻挡！',
                            'description' => '至尊系列200核心800线程',
                            'picurl'      => 'http://img4.imgtn.bdimg.com/it/u=103814481,2107240279&fm=15&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/xeon/xeon-processor-e7-family.html',
                        ),
                        array(
                            'title'       => 'Xeon E3 1200 v6 系列荣耀登场！',
                            'description' => '酷睿系列50核心100线程',
                            'picurl'      => 'http://img5.imgtn.bdimg.com/it/u=4045300680,3181036269&fm=21&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/core/core-i7ee-processor.html',
                        ),
                        array(
                            'title'       => '酷睿code i7 系列荣耀登场！',
                            'description' => '酷睿系列50核心100线程',
                            'picurl'      => 'http://img0.imgtn.bdimg.com/it/u=3035810499,3130754407&fm=21&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/core/core-i7ee-processor.html',
                        ),
                        array(
                            'title'       => '酷睿code i5 系列荣耀登场！',
                            'description' => '酷睿系列50核心100线程',
                            'picurl'      => 'http://img0.imgtn.bdimg.com/it/u=3035810499,3130754407&fm=21&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/core/core-i7ee-processor.html',
                        ),
                    );

                    $templexml = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <ArticleCount>".count($array)."</ArticleCount>
                                    <Articles>";
                    foreach($array as $key=>$val){
                        $templexml .=  "<item>
                                        <Title><![CDATA[".$val['title']."]]></Title>
                                        <Description><![CDATA[".$val['description']."]]></Description>
                                        <PicUrl><![CDATA[".$val['picurl']."]]></PicUrl>
                                        <Url><![CDATA[".$val['url']."]]></Url>
                                        </item>";
                    }
                    $templexml .= "</Articles>
                                   </xml>";

                    $info = sprintf($templexml,$touser,$fromuser,$time,'news');
                    echo $info;
                    exit();
                break;
                case $postObj->Event == 'unsubscribe':
                    $conText    = '您已取消订阅!';
                break;
                case (strtolower($postObj->Event) =='click')://点击菜单事件
                    switch(strtolower($postObj->Event) =='click')
                    {
                        case $postObj->EventKey == 'home':
                            $conText    = '这里是英特尔首页!';
                        break;
                        case $postObj->EventKey == 'introduct':
                            $conText    = '关于英特尔的'.'<a href="http://www.qq.com">简介故事</a>'.'!';
                        break;
                        case $postObj->EventKey == 'e3':
                            $conText    = '各位房东们好！'."<a href='http://www.qq.com'>租客给你们请安了！</a>";
                        break;
                        case $postObj->EventKey == 'i5':
                            $conText    = '英特尔'.'<a href="http://www.qq.com">i5处理器</a>'.'!';
                        break;
                        case $postObj->EventKey == 'i7':
                            $conText    = '英特尔'.'<a href="http://www.qq.com">i7处理器</a>'.'!';
                        break;
                    }
                break;
            }
            $info  = sprintf($temple,$touser,$fromuser,$time,$msgType,$conText);
            echo $info;
        }
        else if($postObj->MsgType == 'text')
        {
            switch($postObj->Content <> '')
            {
                case $postObj->Content==1:
                    $conText    = '您输入的是1！';
                break;
                case $postObj->Content==2:
                    $conText    = '您输入的是2！';
                break;
                case $postObj->Content==3:
                    $conText    = '您输入的是3！';
                break;
                case $postObj->Content=='文章':
                    $array = array(
                        array(
                            'title'       => '至强Xeon E18 家族荣耀登场！',
                            'description' => '至尊系列200核心800线程',
                            'picurl'      => 'http://img4.imgtn.bdimg.com/it/u=103814481,2107240279&fm=15&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/xeon/xeon-processor-e7-family.html',
                        ),
                        array(
                            'title'       => 'Xeon E3 1200 v6 系列荣耀登场！',
                            'description' => '酷睿系列50核心100线程',
                            'picurl'      => 'http://img5.imgtn.bdimg.com/it/u=4045300680,3181036269&fm=21&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/core/core-i7ee-processor.html',
                        ),
                        array(
                            'title'       => '酷睿code i7 系列荣耀登场！',
                            'description' => '酷睿系列50核心100线程',
                            'picurl'      => 'http://img0.imgtn.bdimg.com/it/u=3035810499,3130754407&fm=21&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/core/core-i7ee-processor.html',
                        ),
                        array(
                            'title'       => '酷睿code i5 系列荣耀登场！',
                            'description' => '酷睿系列50核心100线程',
                            'picurl'      => 'http://img0.imgtn.bdimg.com/it/u=3035810499,3130754407&fm=21&gp=0.jpg',
                            'url'         => 'http://www.intel.cn/content/www/cn/zh/processors/core/core-i7ee-processor.html',
                        ),
                    );

                    $templexml = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <ArticleCount>".count($array)."</ArticleCount>
                                    <Articles>";
                            foreach($array as $key=>$val){
                        $templexml .=  "<item>
                                        <Title><![CDATA[".$val['title']."]]></Title>
                                        <Description><![CDATA[".$val['description']."]]></Description>
                                        <PicUrl><![CDATA[".$val['picurl']."]]></PicUrl>
                                        <Url><![CDATA[".$val['url']."]]></Url>
                                        </item>";
                                    }
                    $templexml .= "</Articles>
                                   </xml>";

                    $info = sprintf($templexml,$touser,$fromuser,$time,'news');
                    echo $info;
                    exit();
                break;
                default:
                    $conText    = $postObj->Content;
                break;
            }
            $info  = sprintf($temple,$touser,$fromuser,$time,$msgType,$conText);
            echo $info;
        }

    }

    //抓取功能
    public function use_curl($url,$post='POST',$data=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//PHP取回的URL地址,也可在curl_init()函数初始化时设置
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $post);
        if(!empty($data)){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');//在HTTP请求中包含一个’user-agent’头的字符串。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// 设置这个选项为一个非零值(象 ‘Location: ‘)的头，服务器会把它当做HTTP头的一部分发送(注意这是递归的，PHP将发送形如 ‘Location: ‘的头)。
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);// POST操作的所有数据的字符串
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }

    //获取分享需要用到的ticket
    public function getTicket()
    {
        if($_SESSION['ticket']){
            return $_SESSION['ticket'];
        }else{
            //1.获取jsapi_ticket
            $access_token = $_SESSION['access_token'];
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $res = $this->use_curl($url,'GET');
            json_decode($res,true);
            $_SESSION['ticket'] = $res['ticket'];
            $_SESSION['ticket_expire_time'] = time()+7000;
            return $_SESSION['ticket'];
        } 
    }

    public function getRandCore($num=16)
    {
        $array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                       'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                        '0','1','2','3','4','5','6','7','8','9',
        );
        $randstr = '';
        $max = count($array);
        for($i=1;$i<=$num;$i++)
        {
           $key = rand(1,$max-1);
           $randstr .= $array[$key];
        }
        return $randstr;
    }

    public function share()
    {
        $ticket = $this->getTicket();
        $timestamp = time();
        $nonceStr = $this->getRandCore();
        $signature = '';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>微信js-sdk分享</title>
    <meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1,maximum-scale=1">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="telephone=no" name="format-detection">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="yes" name="apple-touch-fullscreen">
    <script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script><!--引入微信JS-SDK接口文件-->
</head>
<body>
    <p>intel</p>
</body>
<script>
    wx.config({

        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。

        appId: <?php echo APPID;?>, // 必填，公众号的唯一标识

        timestamp: , // 必填，生成签名的时间戳

        nonceStr: '', // 必填，生成签名的随机串

        signature: '',// 必填，签名，见附录1

        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage']

    });

    wx.ready(function(){
        //分享到朋友圈
        wx.onMenuShareTimeline({

            title: '', // 分享标题

            link: '', // 分享链接

            imgUrl: '', // 分享图标

            success: function () {

                // 用户确认分享后执行的回调函数

            },

            cancel: function () {

                // 用户取消分享后执行的回调函数

            }

        });

        //分享给朋友
        wx.onMenuShareAppMessage({

            title: '', // 分享标题

            desc: '', // 分享描述

            link: '', // 分享链接

            imgUrl: '', // 分享图标

            type: '', // 分享类型,music、video或link，不填默认为link

            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空

            success: function () {

                // 用户确认分享后执行的回调函数

            },

            cancel: function () {

                // 用户取消分享后执行的回调函数

            }

        });
    });

    wx.error(function(res){

        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。

    });
</script>
</html>



