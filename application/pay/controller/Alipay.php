<?php
namespace app\pay\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
use AlipaySubmit\AlipaySubmit;
use AlipayNotify\AlipayNotify;

require THINK_PATH.'alipay/alipay_submit.class.php';//发送请求类
require THINK_PATH.'alipay/alipay_notify.class.php';//回调请求类

class Alipay extends Controller
{
    // 初始化
    protected function _initialize()
    {

    }
    //空操作
    public function _empty(){
       return $this->index();
    }

    //设置配置信息
    private function alipay_conf(){
        //合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
        $alipay_config['partner']		= '2088012894780072';
        //收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
        $alipay_config['seller_id']	= $alipay_config['partner'];
        // MD5密钥，安全检验码，由数字和字母组成的32位字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
        $alipay_config['key']			= '5xc5i1xt04rf18oaieglcy683p4iqjco';
        // 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
        $alipay_config['notify_url'] = "http://www.tp5.com/pay/alipay/callback_s";
        // 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
        $alipay_config['return_url'] = "http://www.tp5.com/pay/alipay/callback";
        //签名方式
        $alipay_config['sign_type']    = strtoupper('MD5');
        //字符编码格式 目前支持 gbk 或 utf-8
        $alipay_config['input_charset']= strtolower('utf-8');
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = getcwd().'\\cacert.pem';
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['transport']    = 'http';
        // 支付类型 ，无需修改
        $alipay_config['payment_type'] = "1";
        // 产品类型，无需修改
        $alipay_config['service'] = "create_direct_pay_by_user";
        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

        //↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

        // 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
        $alipay_config['anti_phishing_key'] = "";

        // 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
        $alipay_config['exter_invoke_ip'] = "";

        return $alipay_config;
    }

    //支付提交页面
    public function index()
    {
        if(input('post.')){
            $alipay_config = $this->alipay_conf();
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = input('post.WIDout_trade_no');
            //订单名称，必填
            $subject = input('post.WIDsubject');
            //付款金额，必填
            $total_fee = input('post.WIDtotal_fee');
            //商品描述，可空
            $body = input('post.WIDbody');

            //构造要请求的参数数组，无需改动
            $parameter = array(
                "service"       => $alipay_config['service'],
                "partner"       => $alipay_config['partner'],
                "seller_id"     => $alipay_config['seller_id'],
                "payment_type"	=> $alipay_config['payment_type'],
                "notify_url"	=> $alipay_config['notify_url'],
                "return_url"	=> $alipay_config['return_url'],
                "anti_phishing_key"=>$alipay_config['anti_phishing_key'],
                "exter_invoke_ip"=>$alipay_config['exter_invoke_ip'],
                "out_trade_no"	=> $out_trade_no,
                "subject"	=> $subject,
                "total_fee"	=> $total_fee,
                "body"	=> $body,
                "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
                //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
                //如"参数名"=>"参数值"

            );
            //建立请求
            $alipaySubmit = new AlipaySubmit($alipay_config);
            $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
            echo $html_text;
        }

        return $this->fetch('index');
    }
    //同步通知
    public function callback()
    {
        //计算得出通知验证结果
        $alipay_config = $this->alipay_conf();
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $out_trade_no = input('get.out_trade_no');
            //支付宝交易号
            $trade_no = input('get.trade_no');
            //交易状态
            $trade_status = input('get.trade_status');


            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            }
            else {
                echo "trade_status=".$_GET['trade_status'];
            }

            echo "验证成功<br />";

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }
    }


    //异步通知
    public function callback_s(){
        //计算得出通知验证结果
        $alipay_config = $this->alipay_conf();
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) {
            //验证成功

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——


            //商户订单号
            $out_trade_no = input('post.out_trade_no');
            //支付宝交易号
            $trade_no = input('post.trade_no');
            //交易状态
            $trade_status = input('post.trade_status');


            if($trade_status == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else if ($trade_status == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";		//请不要修改或删除

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
}
