<?php
//===========================================================================================================

//打印函数
function p($val) {
    echo '<pre>';
    print_r($val);
    echo '</pre>';
}

function ps($val, $dump = 1, $die = true) {
    if ($dump != 1) {
        $fun = 'var_dump';
    } else {
        $fun = (is_array($val) || is_object($val)) ? 'print_r' : 'printf';
    }
    header('Content-type:text/html;charset=utf-8');
    echo '<pre>';
    $fun($val);
    echo '</pre>';
    if ($die)
        die;
}
//===========================================================================================================

//发送邮箱验证码
function send_eamil(){
    require(EXTEND_PATH.'/class_mail/class.phpmailer.php');

    $mail = new PHPMailer(); //实例化
    $mail->IsSMTP(); // 启用SMTP
    $mail->Host = "smtp.qq.com"; //SMTP服务器 以163邮箱为例子
    $mail->Port = 465;  //邮件发送端口
    $mail->SMTPAuth   = true;  //启用SMTP认证

    $mail->CharSet  = "UTF-8"; //字符集
    $mail->Encoding = "base64"; //编码方式

    $mail->Username = "534012657@qq.com";  //你的邮箱
    $mail->Password = "email12345";  //你的密码
    $mail->Subject = "恭喜你已中奖！"; //邮件标题

    $mail->From = "534012657@qq.com";  //发件人地址（也就是你的邮箱）
    $mail->FromName = "马云";  //发件人姓名

    $address = "528290768@qq.com";//收件人email
    $mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

    //$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
    $mail->IsHTML(true); //支持html格式内容
    //$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
    $mail->Body = '你好, <b>朋友</b>! <br/>这是一封来自<a href="http://www.helloweba.com" target="_blank">helloweba.com</a>的邮件！<br/><img alt="helloweba" src="cid:my-attach">'; //邮件主体内容

    //发送
    if(!$mail->Send()) {
        echo "发送失败: " . $mail->ErrorInfo;
    } else {
        //$_SESSION['ip'] = get_client_ip();
        //$_SESSION['time'] = time();
        echo "发送成功！";
    }
}

//验证是否为邮箱
function isEmail($email){
    if(!$email) return false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

//验证是否为手机
function isPhone($str){
    $pattern = "/^1(3[0-9]|4[57]|5[0-35-9]|7[0135678]|8[0-9])\\d{8}$/";
    if(!$str) return false;
    if (!preg_match($pattern, $str)) return false;
    return true;
}

function create_randomstr($lenth = 4) {
    return substr(str_shuffle('abcdefghijklmnpqrstuvwxyz123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),0,$lenth);
}

function password($password, $encrypt = '') {
    $pwd = array();
    $pwd['encrypt'] = $encrypt ? $encrypt : create_randomstr();
    $pwd['password'] = md5(md5(trim($password)) . $pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

//验证密码是否合法
function isPassword($password){
    $reg = "/^[a-zA-Z\d_]{6,16}$/";
    $strlen = strlen(trim($password));
    if(!$password) return false;
    if (!$strlen >= 6 && !$strlen <= 16)
        return false;

    if (!preg_match($reg,$password)) {
        return false;
    }
    return true;
}


/**
 * 检测输入中是否含有错误字符
 *
 * @param char $string 要检查的字符串名称
 * @return TRUE or FALSE
 */
function is_badword($string) {
    $badwords = array("\\", '&', ' ', "'", '"', '/', '*', ',', '<', '>', "\r", "\t", "\n", "#");
    foreach ($badwords as $value) {
        if (strpos($string, $value) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * 检查用户名是否符合规定 (两个字符以上，只能有中文，字母，数字，下划线的)
 *
 * @param STRING $username 要检查的用户名
 * @return  TRUE or FALSE
 */
function isUsername($username){
    $strlen = strlen($username);
    if (is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)) {
        return false;
    } elseif (15 < $strlen || $strlen <= 3) {
        return false;
    }

    return true;
}

//判断用户是否登录
function get_user_info($uid=null){
    if(!$uid)
    {
        if(!session('login'))  return false;
        $user_session = session('login');
        $uid = $user_session['uid'];
        }

    $user_info = db('user')->where(array('uid'=>$uid))->find();
    return $user_info;
}

/*
 * 中文字符截取
 * @param      string        $string       被处理的字符串
 * @param      int           $start        开始截取的位置
 * @param      int           $length       截取的字符长度
 * @param      string        $dot          缩略符号
 * @param      string        $charset      字符编码
 * @return       string      $new          成功截取后的字符串
 */

function jiequ($string, $start, $length, $dot = '', $charset = "utf-8") {
    //判断当前的环境中是否开启了mb_substr这个函数
    if (function_exists("mb_substr")) {

        if (mb_strlen($string, $charset) > $length) {
            //如果开启了就可以直接使用这个
            return mb_substr($string, $start, $length, $charset) . $dot;
        }
        return mb_substr($string, $start, $length, $charset);
    }
    //否则就是下面没开启
    $new = '';
    //判断是否是gbk，是gbk就转码成utf-8
    if ($charset === 'gbk') {
        $string = iconv("gbk", "utf-8", $string);
    }
    //下面这个只能使用在utf-8的编码格式中
    $str = preg_split('//u', trim($string));
    for ($i = $start, $len = 1; $i < count($str) - 1 && $len <= $length; $i++, $len++) {
        $new .= $str[$i + 1];
    }
    //如果是gbk，就需要在返回结果之前，把之前的转换编码恢复一下
    if ($charset === 'gbk') {
        $new = iconv("utf-8", "gbk", $new);
    }
    return count($str) - 2 < $length ? $new : $new . $dot;
}

/*
 * 时间戳转换
 *
 *
 */

function wordtime($time) {
    $time = (int) substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ($int <= 10) {
        $str = sprintf('刚刚', $int);
    } elseif ($int < 60) {
        $str = sprintf('%d秒前', $int);
    } elseif ($int < 3600) {
        $str = sprintf('%d分钟前', floor($int / 60));
    } elseif ($int < 86400 / 2) {
        $str = sprintf('%d小时前', floor($int / 3600));
    } elseif ($int < 86400 * 2) {
        $str = sprintf('%d天前', floor($int / 86400));
    } else {
        $str = date('m月d日 H:i:s', $time);
    }
    return $str;
}

//发送邮件验证码
function send_emailCode($email, $title = '', $content = '', $username = '') {
    require(EXTEND_PATH.'/PHPMailer/cls_phpmailer.php');

    if (!$title) {
        $title = "感谢您的注册," . $username . "请查收验证码";
    }

    if (!$content) {
        $code = rand(1000,9999);
        $content = <<<AAA
    <p>
    <br/>
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal;">
    <span style="line-height: 23.8px; font-family: 微软雅黑, &#39;Microsoft YaHei&#39;;">尊敬的用户：</span>
</p><span style="font-family: 微软雅黑, &#39;Microsoft YaHei&#39;; line-height: 23.8px; font-size: 14px;">您好！您正在申请注册</span><br/>
<p style="line-height: 23.8px; font-size: 14px; white-space: normal;">
    <span style="font-family:微软雅黑, Microsoft YaHei">验证码为<span style="color:#00b0f0">$code</span>，请您在15分钟内输入。</span>
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal;">
    <span style="line-height: 23.8px; font-family: 微软雅黑, &#39;Microsoft YaHei&#39;;">祝您使用愉快.</span>
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal;">
    <span style="line-height: 23.8px; font-family: 微软雅黑, &#39;Microsoft YaHei&#39;;">系统发信，请勿回复</span>
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal;">
    <span style="line-height: 23.8px; font-family: 微软雅黑, &#39;Microsoft YaHei&#39;;">七里香官网：<a href="http://www.iouhai.top" target="_blank" title="http://www.iouhai.top"><span style="color:#00b0f0">www.iouhai.top</span></a></span>
</p>
AAA;

        //$content = "<h3>您的验证码是：<font color='red'><b>" . $code . " </b></font>有效期为15分钟。 为了您的账号安全，请勿把证码泄露验给其他人。</h3>";
    }

    $mail = new PHPMailer();
    $mail->CharSet  = "UTF-8";                   //可以解决乱码问题
    $mail->IsSMTP();                            // 经smtp发送
    $mail->Host     = "smtp.163.com";            // SMTP 服务器
    $mail->Port     = 25;                          //邮件发送端口
    $mail->SMTPAuth = true;                     // 打开SMTP 认证
    $mail->Username = "iouhai2016@163.com";        // 用户名
    $mail->Password = "AABB12345";             // 密码
    $mail->From     = "iouhai2016@163.com";           //发件人地址（也就是你的邮箱）
    $mail->FromName = "七里香";                  //发件人姓名
    $mail->AddAddress($email);                  // 收信人
    //$mail->WordWrap = 50;
    $mail->IsHTML(true);                        // 以html方式发送
    $mail->Subject  = $title;                  // 邮件标题
    $mail->Body     = $content;                        // 邮件内空
    $result         = $mail->Send();
    //$mail->AltBody  =  "请使用HTML方式查看邮件。";
    if($result){
        return true;
    }else{
        echo "发送失败: " . $mail->ErrorInfo;
    }
}
//阿里云邮件推送
function AliyunEmail(){
    require(EXTEND_PATH.'/aliyunMailer/aliyun-php-sdk-core/Config.php');
    //use Dm\Request\V20151123 as Dm;
    $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAIsGapgBCquWV6", "2fmwpXWwLeKm5bu7RULCPTYnK4hRzz");
    $client = new DefaultAcsClient($iClientProfile);
    $request = new Dm\SingleSendMailRequest();
    $request->setAccountName("控制台创建的发信地址");
    $request->setFromAlias("发信人昵称");
    $request->setAddressType(1);
    $request->setTagName("控制台创建的标签");
    $request->setReplyToAddress("true");
    $request->setToAddress("目标地址");
    $request->setSubject("邮件主题");
    $request->setHtmlBody("邮件正文");
    try {
        $response = $client->getAcsResponse($request);
    }
    catch (ClientException  $e) {
        print_r($e->getErrorCode());
        print_r($e->getErrorMessage());
    }
    catch (ServerException  $e) {
        print_r($e->getErrorCode());
        print_r($e->getErrorMessage());
    }
}

function send_mobile_code($mobile,$content=''){
    $code = rand(100000,999999);
    if(!$content) $content='您的验证码是：'.$code.'。请不要把验证码泄露给其他人。';
    $url = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
    $send_data = array(
        'account'  => 'cf_jieshun',
        'password' => '123456',
        'mobile'   => $mobile,
        'content'  => $content,
        'format'   => 'json',//返回结果 可选xml和json
    );
    $res = curl_get_content($url,$send_data,'post');
    $res = json_decode($res,true);

    if($res['code']==2){
        $result['code']    = $code;
        $result['msg']     = '验证码已经下发，请注意查收';
        $result['result']  = 1;
        return $result;
    }else{
        $result['msg']     = $res['msg'];
        $result['result']  = 0;
        return $result;
    }
}
/*          curl发送请求
 *$url      请求链接
 *$data     请求数据
 *$method   请求方式
 */
function curl_get_content($url='',$data='',$method='get'){
    //初始化
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_NOBODY, true);

    // post发送请求
    if($method=='post') curl_setopt($ch, CURLOPT_POST, 1);
    // 发送的数据
    if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $get_data = curl_exec($ch);
    curl_close($ch);
    return $get_data;
}
//把xml转换成数组
function xml_to_array($xml) {
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    $arr = array();
    if (preg_match_all($reg, $xml, $matches)) {
        $count = count($matches[0]);
        for ($i = 0; $i < $count; $i++) {
            $subxml = $matches[2][$i];
            $key = $matches[1][$i];
            if (preg_match($reg, $subxml)) {
                $arr[$key] = xml_to_array($subxml);
            } else {
                $arr[$key] = $subxml;
            }
        }
    }

    return $arr;
}

//把对象转换成数组
function object_to_array($object_){
    $arr = array();
    if(is_object($object_)){
        foreach($object_ as $k => $v){
            $arr[$k] = $v;
        }
    }else{
        $arr = $object_;
    }
    return $arr;
}

function Post($curlPost, $url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $return_str = curl_exec($curl);
    curl_close($curl);
    return $return_str;
}


function pc_404($msg = '不好意思，您访问的页面不存在~',$url = '') {
  $url = $url ? url($url) : url('start/index/index');
  $arr = <<<AAA
    <!DOCTYPE HTML>
    <html>
    <head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/public/static/admin/lib/html5.js"></script>
    <script type="text/javascript" src="/public/static/admin/lib/respond.min.js"></script>
    <script type="text/javascript" src="/public/static/admin/lib/PIE_IE678.js"></script>
    <![endif]-->
    <link href="/public/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
    <link href="/public/static/admin/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="/public/static/admin/static/h-ui.admin/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>404页面</title>
    </head>
    <body>
    <section class="container-fluid page-404 minWP text-c">
      <p class="error-title"><i class="Hui-iconfont va-m" style="font-size:80px">&#xe688;</i><span class="va-m"> 404</span></p>
      <p class="error-description">$msg</p>
      <p class="error-info">您可以：<a href="javascript:;" onclick="history.go(-1)" class="c-primary">&lt; 返回上一页</a><span class="ml-20">|</span><a href="$url" class="c-primary ml-20">看看别的 &gt;</a></p>
    </section>
    </body>
    </html>
AAA;
    echo $arr;
    exit();
}


function js_stopAction(){
    $arr = <<<AAA
<script>
    //禁止鼠标右键以及F12
    function click(e) {
        if (document.all) {
            if (event.button==2||event.button==3) { alert("欢迎光临寒舍，有什么需要帮忙的话，请与站长联系！谢谢您的合作！！！");
                oncontextmenu='return false';
            }
        }
        if (document.layers) {
            if (e.which == 3) {
                oncontextmenu='return false';
            }
        }
    }
    if (document.layers) {
        document.captureEvents(Event.MOUSEDOWN);
    }
    document.onmousedown=click;
    document.oncontextmenu = new Function("return false;")
    document.onkeydown =document.onkeyup = document.onkeypress=function(){
        if(window.event.keyCode == 123) {
            window.event.returnValue=false;
            return(false);
        }
    }
    //屏蔽右键菜单
    document.oncontextmenu = function(event) {
        if (window.event) {
            event = window.event;
        }
        try {
            var the = event.srcElement;
            if (!((the.tagName == "INPUT" && the.type.toLowerCase() == "text") || the.tagName == "TEXTAREA")) {
                return false;
            }
            return true;
        } catch (e) {
            return false;
        }
    }

    //屏蔽选中
    document.onselectstart = function(event) {
        if (window.event) {
            event = window.event;
        }
        try {
            var the = event.srcElement;
            if (!((the.tagName == "INPUT" && the.type.toLowerCase() == "text") || the.tagName == "TEXTAREA")) {
                return false;
            }
            return true;
        } catch (e) {
            return false;
        }
    }
</script>
AAA;
 echo $arr;
}


/**
 * 数据转csv格式的excle
 * @param  array $data      需要转的数组
 * @param  string $header   要生成的excel表头
 * @param  string $filename 生成的excel文件名
 *      示例数组：
$data = array(
'1,2,3,4,5',
'6,7,8,9,0',
'1,3,5,6,7'
);
$header='用户名,密码,头像,性别,手机号';
 */
function create_excel($data,$header=null,$filename='simple.csv'){
    // 如果手动设置表头；则放在第一行
    if (!is_null($header)) {
        array_unshift($data, $header);
    }
    // 防止没有添加文件后缀
    $filename=str_replace('.csv', '', $filename).'.csv';
    ob_clean();
    Header( "Content-type:  application/octet-stream ");
    Header( "Accept-Ranges:  bytes ");
    Header( "Content-Disposition:  attachment;  filename=".$filename);
    foreach( $data as $k => $v){
        // 如果是二维数组；转成一维
        if (is_array($v)) {
            $v=implode(',', $v);
        }
        // 替换掉换行
        $v=preg_replace('/\s*/', '', $v);
        // 解决导出的数字会显示成科学计数法的问题
        $v=str_replace(',', "\t,", $v);
        // 转成gbk以兼容office乱码的问题
        echo iconv('UTF-8','GBK',$v)."\t\r\n";
    }
}

//获取用户ip
function get_ip(){
    $realip = '';
    $unknown = 'unknown';
    if (isset($_SERVER)){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach($arr as $ip){
                $ip = trim($ip);
                if ($ip != 'unknown'){
                    $realip = $ip;
                    break;
                }
            }
        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
            $realip = $_SERVER['REMOTE_ADDR'];
        }else{
            $realip = $unknown;
        }
    }else{
        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
            $realip = getenv("HTTP_CLIENT_IP");
        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
            $realip = getenv("REMOTE_ADDR");
        }else{
            $realip = $unknown;
        }
    }
    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
    return $realip;
}

//根据ip地址获取地址信息
function get_ip_address($ip='',$return_type=1){
    if(empty($ip)) $ip = get_ip();

    $urlTaobao = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
    $urlSina = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$ip;
    $json = @curl_get_content($urlTaobao);
    $jsonDecode = json_decode($json,true);

    if($jsonDecode['code']==0){//如果取不到就去取新浪的
        $data['country']  = $jsonDecode['data']['country'];
        $data['province'] = $jsonDecode['data']['region'];
        $data['city']     = $jsonDecode['data']['city'];
        $data['isp']      = $jsonDecode['data']['isp'];
    }else{
        $json = @file_get_contents($urlSina);
        $jsonDecode = json_decode($json);
        $data['country'] = $jsonDecode->country;
        $data['province'] = $jsonDecode->province;
        $data['city'] = $jsonDecode->city;
        $data['isp'] = $jsonDecode->isp;
        //$data['district'] = $jsonDecode->district;
    }
    if($return_type) return implode(' ',$data);//默认返回字符串，也可以返回数组

    return $data;
}

//根据ip获取地址
function get_ipCity($ip = ''){
    if(empty($ip)){
        $ip = get_ip();
    }
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip);
    if(empty($res)){ return false; }
    $jsonMatches = array();
    preg_match('#\{.+?\}#', $res, $jsonMatches);
    if(!isset($jsonMatches[0])){ return false; }
    $json = json_decode($jsonMatches[0], true);
    if(isset($json['ret']) && $json['ret'] == 1){
        $json['ip'] = $ip;
        unset($json['ret']);
    }else{
        return false;
    }
    $arr = array();
    $arr['guo'] = $json['country'];
    $arr['sheng'] = $json['province'];
    $arr['shi'] = $json['city'];
    return $arr;
}
//前端geetest代码封装
function show_geetest_code($create_url='',$checked_url=''){
    if(!$create_url)  $create_url=url('start/index/get_geetest_code');
    if(!$checked_url) $checked_url=url('start/index/check_geetest_code');
    $html_ = <<<AAA
    <script src="http://static.geetest.com/static/tools/gt.js"></script><!--geetest验证码js库-->
    <script>
       var handler = function (captchaObj) {
            // 将验证码加到id为captcha的元素里
            captchaObj.appendTo("#captcha");
        };
        // 获取验证码
        $.get('$create_url', function(data) {
            // 使用initGeetest接口
            // 参数1：配置参数，与创建Geetest实例时接受的参数一致
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                product: "float", // 产品形式
                offline: !data.success
            }, handler);
        },'json');
        // 检测验证码
        function check_code(obj){
            // 验证需要用的数据
            var postData={
                geetest_challenge: $('.geetest_challenge').val(),
                geetest_validate: $('.geetest_validate').val(),
                geetest_seccode: $('.geetest_seccode').val()
            }
            // 验证是否通过
            $.post('$checked_url', postData, function(data) {
                if(data.status==0){
                    layer.msg(data.msg,{icon:6,offset: ['100px'],skin: 'layui-layer-molv',time:2000});
                    return false;
                }
                //layer.msg(data.msg,{icon:1,offset: ['100px'],skin: 'layui-layer-molv',time:1000});
            },'json');
        }
    </script>
AAA;
echo $html_;
}

//生成滑动验证码
function create_slide_code(){
    require EXTEND_PATH . '/slide_code/lib/class.geetestlib.php';
    require EXTEND_PATH . '/slide_code/config/config.php';
    $GtSdk = new GeetestLib(CAPTCHA_ID,PRIVATE_KEY);
    $user_id = "test";
    $status = $GtSdk->pre_process($user_id);
    session('slide_code',array('gtserver'=>$status,'user_id'=>$user_id));
    echo $GtSdk->get_response_str();
}


/**
 * geetest检测验证码
 */
function chcek_slide_code($data){
    require EXTEND_PATH . '/slide_code/lib/class.geetestlib.php';
    require EXTEND_PATH . '/slide_code/config/config.php';
    $GtSdk   = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
    $info    = session('slide_code');
    if ($info['gtserver'] == 1) {
        $result = $GtSdk->success_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'],$info['user_id']);
        if ($result) {
            return true;
        } else{
            return false;
        }
    }else{
        if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
            return true;
        }else{
            return false;
        }
    }
}

//获取管理员权限
function get_admin_ugid(){
    $admin_info = session('admin');
    $res = db('admin')->where(array('userid'=>$admin_info['uid']))->find();
    if($res['agid']==0){
        return false;
    }
    return true;
}

//生成kingeditor编辑器
function get_kindeditor($name){
    require EXTEND_PATH . '/kindeditor/kindeditor.php';
    return Kindeditor::editor($name);
}

function kindeditor_content_img($content){
    $content = stripslashes($content);
    preg_match_all("/src=\"(.*?)\"/is", $content, $match);
    if ($match[1]) {
        $save_path = ROOT_PATH.'public/upload/kindeditor';
        $save_url = '/public/upload/kindeditor/';
        $max_size = 10000000;
        $save_path = realpath($save_path) . '/';
        $ymd = date("Ymd");
        $save_path .= $ymd . "/";
        $save_url .= $ymd . "/";
        if (!file_exists($save_path)){
            mkdir($save_path);
        }
        foreach ($match[1] as $imgsrc) {
            if (strpos($imgsrc, 'base64') !== false) {
                $imgdata = base64_decode(str_replace('data:image/png;base64,', '', $imgsrc));
                $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.png';
                $file_path = $save_path . $new_file_name;
                file_put_contents($file_path, $imgdata);
                @chmod($file_path, 0644);
                $file_url = $save_url . $new_file_name;
                $content = str_replace($imgsrc, $file_url, $content);
            }
        }
    } else {
        return $content;
    }

    return $content;
}



/**
 * [bubble_sort 冒泡排序]
 * @param  [array] $data [需要排序的数据（相邻的数据进行比较调换位置）]
 * @return [array]       [排序好的数据]
 */
function bubble_sort($data)
{
    if(!empty($data) && is_array($data))
    {
        $len = count($data);
        for($i=0; $i<$len; $i++)
        {
            for($k=0; $k<$len-1; $k++)
            {
                if($data[$k] > $data[$i])
                {
                    $data[$i] = $data[$i] ^ $data[$k];
                    $data[$k] = $data[$i] ^ $data[$k];
                    $data[$i] = $data[$i] ^ $data[$k];
                }
            }
        }
    }
    return $data;
}

/**
 * [select_sort 选择排序]
 * @param  [array] $data [需要排序的数据（选择最小的值与第一个调换位置）]
 * @return [array]       [排序好的数据]
 */
function select_sort($data)
{
    if(!empty($data) && is_array($data))
    {
        $len = count($data);
        for($i=0; $i<$len; $i++)
        {
            $t = $i;
            for($j=$i+1; $j<$len; $j++)
            {
                if($data[$t] > $data[$j])
                {
                    $t = $j;
                }
            }
            if($t != $i)
            {
                $data[$i] = $data[$i] ^ $data[$t];
                $data[$t] = $data[$i] ^ $data[$t];
                $data[$i] = $data[$i] ^ $data[$t];
            }
        }
    }
    return $data;
}

/**
 * [insert_sort 插入排序]
 * @param  [array] $data [需要排序的数据（把第n个数插到前面的有序数组中，以此反复循环直到排序好）]
 * @return [array]       [排序好的数据]
 */
function insert_sort($data)
{
    if(!empty($data) && is_array($data))
    {
        $len = count($data);
        for($i=1; $i<$len; $i++)
        {
            $tmp = $data[$i];
            for($j=$i-1; $j>=0; $j--)
            {
                if($data[$j] > $tmp)
                {
                    $data[$j+1] = $data[$j];
                    $data[$j] 	= $tmp;
                } else {
                    break;
                }
            }
        }
    }
    return $data;
}

/**
 * [quick_sort 快速排序]
 * @param  [array] $data [需要排序的数据（选择一个基准元素，将待排序分成小和打两罐部分，以此类推递归的排序划分两罐部分）]
 * @return [array]       [排序好的数据]
 */
function quick_sort($data)
{
    if(!empty($data) && is_array($data))
    {
        $len = count($data);
        if($len <= 1) return $data;

        $base = $data[0];
        $left_array = array();
        $right_array = array();
        for($i=1; $i<$len; $i++)
        {
            if($base > $data[$i])
            {
                $left_array[] = $data[$i];
            } else {
                $right_array[] = $data[$i];
            }
        }
        if(!empty($left_array)) $left_array = quick_sort($left_array);
        if(!empty($right_array)) $right_array = quick_sort($right_array);

        return array_merge($left_array, array($base), $right_array);
    }
}


//获取天气预报
function getWeather($city='',$date=''){
    if(!$city) $city = get_ipCity();
    if(!$date){
        $date = date('Ym',time());
    }
    $host = "http,https://weather.market.alicloudapi.com";
    $path = "/weatherhistory";
    $appcode = "027e1d0273b246fc8214ed36892c19fc";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "area=%E4%B8%BD%E6%B1%9F&area={$city}&month={$date}";
    $bodys = "";
    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    var_dump(curl_exec($curl));
}

//实例化微信公众号--SDK类
 function new_weixin_class(){
    require EXTEND_PATH.'weixin_class/wechat.class.php';
    //微信公众测试号配置
    $config = array(
        'token'=>'weixin', //填写你设定的key
        'appid'=>'wx06eac275bfb8ed67',                   //测试号
        'appsecret'=>'a242aa44c3059c45c8a9b3dc9a8c7697',  //测试号
        //'appid'=>'wx986dbbf9c1a3d101',                     //订阅号
        //'appsecret'=>'5109cc81efb55f62c88973f3f92e2321',   //订阅号
        //'encodingaeskey'=>'EF6ZgvcmEcpY3FAMcTFioJPF9xUg25ATZEJTjfP6PrO', //订阅号填写加密用的EncodingAESKey
    );

    return new \Wechat($config);
}
//发送登录-微信模板消息
function weixin_send_login_message($uid){
    $weixin   = new_weixin_class();
    $userinfo = get_user_info($uid);
    if(!$userinfo['weixin_openid']) return false;
    $openid = 'oX7b7waIv8NZmCDLV270NUre8Bzs';


    $ip      = request()->ip();
    $address = get_ip_address($ip);


    $data = array(
        'touser'      => $userinfo['weixin_openid'],
        'template_id' => 'mOdpebKBew0JjuPB1wD-aUAmN9UdzVewsEM-Y3TNDU8',//微信模板消息id
        'topcolor'    => '#7b68ee',
        'data'        => array(
            'name1'   => array('color' => '#000'    , 'value' => $userinfo['username']),
            'name2'   => array('color' => '#000'    , 'value' => $userinfo['username']),
            'date'    => array('color' => '#000'    , 'value' => date('Y年m月d日 H:i:s')),
            'ip'      => array('color' => '#000'    , 'value' => $ip),
            'address' => array('color' => '#000'    , 'value' => $address),
            'remark'  => array('color' => '#000'    , 'value' => '如有疑问，请登录http://iouhai.top联系在线客服'),
        ),
    );
    return $weixin->sendTemplateMessage($data);
}

?>