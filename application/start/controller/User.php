<?php
namespace app\start\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
use Dm\Request\V20151123 as Dm;//阿里云邮件推送

class User extends Controller
{
    // 初始化
    protected function _initialize()
    {
        //判断是否已经登录
        if(!session('login')){
            $url = url('start/index/index');
            header('Location: '.$url);
            die;
        }
    }

    public function _empty()
    {
        pc_404();
    }


    //获取导航菜单
    private function get_nav_menu(){

        $nav_data = cache('nav_data');
        if(empty($nav_data)){
            $nav_data = Db::name('nav')->where(array('pid'=>0))->select();
            foreach($nav_data as $key=>$val){
                $childdren = Db::name('nav')->where(array('pid'=>$val['id']))->select();
                $nav_data[$key]['children'] = $childdren;
            }
            cache('nav_data',$nav_data);
        }

        $user_info = get_user_info();
        $this->assign('user_info',$user_info);
        $this->assign('nav_data',$nav_data);
    }

    //个人中心
    public function index(){
        $this->get_nav_menu();
        //修改用户信息
        if($_POST){
            $user_session = session('login');
            $realname  = input('post.realname');
            $sex       = input('post.sex');
            $birthday  = strtotime(input('post.birthday'));
            $qq        = input('post.qq');
            $introduce = htmlspecialchars(input('post.introduce'));
            $data = array(
                'realname'  => $realname,
                'sex'       => $sex,
                'birthday'  => $birthday,
                'qq'        => $qq,
                'introduce' => $introduce,
            );
            $res = Db::name('user')->where(array('uid'=>$user_session['uid']))->update($data);
            if($res) exit(json_encode(array('success'=>1,'msg'=>'修改成功')));
            else exit(json_encode(array('error'=>1,'msg'=>'修改遇到了一点问题~ 请稍后重试')));
        }else{
            return $this->fetch('index/user/user');
        }
    }

    //个人中心--安全设置
    public function security(){
        $this->get_nav_menu();

        return $this->fetch('index/user/security');
    }

    //发送验证码
    public function send_code(){
        if(request()->isAjax()){
            $user_session     = session('login');
            $user_info        = get_user_info();
            $send_num         = input('post.send_num');
            $verify           = input('post.verify');
            $action           = input('post.action');//1绑定手机2更换手机3绑定邮箱4更换邮箱5修改密码
            $type             = intval(input('post.type'));  //发送验证方式：1手机验证2邮箱验证
            $type             = $type?intval(input('post.type')):0;

            if($verify==1){ //是否为为验证身份
                if($type==1){
                    $send_num = $user_info['mobile'];
                }elseif($type==2){
                    $send_num = $user_info['email'];
                }
            }

            if(($type==1) && (isPhone($send_num)==false)) exit(json_encode(array('error'=>1,'msg'=>'请输入正确手机号码')));
            if(($type==2) && (isEmail($send_num)==false)) exit(json_encode(array('error'=>1,'msg'=>'请输入正确邮箱号码')));

            if(isPhone($send_num)){

                //查询验证码是否在15分钟有效期之内
                $res = Db::name('send_code')->where(array('uid'=>$user_session['uid'],'mobile'=>$send_num,'type'=>$type,'action'=>$action))->order(['id'=>'desc'])->find();
                if($res['addtime']+15*60 > time()) exit(json_encode(array('error'=>1,'msg'=>'验证码15分钟之内有效，请勿重复发送')));

                //统一发送绑定或者修改手机号验证码
                $send_result = send_mobile_code($send_num);
                //发送成功
                if($send_result['result']==1){
                    $data = array(
                        'uid'      => $user_session['uid'],
                        'mobile'   => $send_num,
                        'code'     => $send_result['code'],
                        'type'     => $type,
                        'action'   => $action,
                        'addtime'  => time(),
                    );
                    Db::name('send_code')->insert($data);

                    exit(json_encode(array('success'=>1,'msg'=>$send_result['msg'])));
                }elseif($send_result['result']==0){
                    //发送失败
                    exit(json_encode(array('error'=>1,'msg'=>$send_result['msg'])));
                }
            }

            //邮箱验证码
            elseif(isEmail($send_num)){
                $email_s = substr_replace($send_num,'****',3,4);
                $res = Db::name('send_code')->where(array('uid'=>$user_session['uid'],'email'=>$send_num,'type'=>$type,'action'=>$action))->order('id desc')->find();
                if($res && $res['addtime']+15*60 > time()) exit(json_encode(array('error'=>1,'msg'=>'邮件已经发出，15分钟之内请勿重发')));



                //发送邮箱验证码

            $res1 = Db::name('user')->where("email='{$send_num}' and uid != {$user_session['uid']}")->find();
            if($res1) exit(json_encode(array('error'=>1,'msg'=>'此邮箱已被绑定，请更换其他邮箱')));

            $code = rand(100000,999999);
            $title = '尊敬的用户你好，请查收验证码';
            $content =<<<AAA
    <p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);">
    亲爱的{$email_s}, 您好:
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);">
    <span style="font-family: 微软雅黑, &#39;Microsoft YaHei&#39;;">本次验证码为<span style="font-family: 微软雅黑, &#39;Microsoft YaHei&#39;; color: rgb(0, 176, 80);">{$code}</span>，请您在15分钟内输入。如非本人操作，可不用理会。</span>
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);">
    祝您使用愉快.
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);">
    系统发信，请勿回复
</p>
<p style="line-height: 23.8px; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);">
    <span style="font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; line-height: 23.8px; background-color: rgb(255, 255, 255);">七里香官网：</span><a href="http://www.iouhai.top/" target="_blank" title="http://www.iouhai.top" style="outline: none; cursor: pointer; font-family: &#39;lucida Grande&#39;, Verdana, &#39;Microsoft YaHei&#39;; font-size: 14px; line-height: 23.8px; white-space: normal; color: rgb(247, 150, 70); text-decoration: underline; background-color: rgb(255, 255, 255);"><span style="color: rgb(247, 150, 70);">www.iouhai.top</span></a><span style="outline: none; cursor: pointer; color: rgb(0, 176, 240); text-decoration: none;"></span>
</p>
AAA;



                $send_code = send_emailCode($send_num,$title,$content);
                if($send_code){
                    $data = array(
                        'uid'     =>  $user_session['uid'],
                        'email'   =>  $send_num,
                        'code'    =>  $code,
                        'action'  =>  $action,
                        'type'    =>  $type,
                        'addtime' =>  time(),
                    );
                    Db::name('send_code')->insert($data);
                    exit(json_encode(array('success'=>1,'msg'=>'邮件已发送，请登录邮箱查收')));
                }

            }

        }else{
            pc_404();
        }
    }


    //修改密码-电话号码-邮箱身份验证
    public function verify_people(){
        if(request()->isAjax()){
            $user_info   = get_user_info();
            $num         = input('post.num');   //接受的数据
            $action      = input('post.action');// 1绑定手机2更换手机3绑定邮箱4更换邮箱5修改密码
            $type        = input('post.type');  // 1手机验证2邮箱验证

            if($type==1 or $type==2) $data = array('uid'=>$user_info['uid'],'code'=>$num,'action'=>$action,'type'=>$type);

            switch($type != ''){
                case ($type==0):
                    $password = password($num,$user_info['encrypt']);
                    if($password != $user_info['password']) exit(json_encode(array('error'=>1,'msg'=>'验证失败，密码错误')));
                    session('action',array('action'=>$action,'time'=>time()));
                    exit(json_encode(array('success'=>1,'msg'=>'验证成功，请进行下一步操作')));
                    break;
                case ($type==1):
                    $data['mobile'] = $user_info['mobile'];
                    break;
                case ($type==2):
                    $data['email']  = $user_info['email'];
                    break;
                default:
            }
            session('action',array('action'=>$action,'time'=>time()));
            $res = Db::name('send_code')->where($data)->order('id desc')->find();
            if(!$res) exit(json_encode(array('error'=>1,'msg'=>'验证失败，验证码不正确')));
            if($res['addtime']+15*60 < time()) exit(json_encode(array('error'=>1,'msg'=>'验证失败，验证码已过期')));
            exit(json_encode(array('success'=>1,'msg'=>'验证成功，请进行下一步操作')));
        }else{
            pc_404();
        }
    }

    //设置或修改手机号
    public function mobile(){
        if($_POST){
            $user_info        = get_user_info();
            $mobile           = input('post.mobile');
            $mobile_code      = input('post.mobile_code');
            $action           = input('post.action');
            $action           = $action ? $action:'';

            if(empty($user_info['mobile'])){
                $sreach_result = Db::name('user')->where(array('mobile'=>$mobile))->find();
            }else{
                $session_action = session('action');
                if(!$session_action or $session_action['action']!=2) exit(json_encode(array('error'=>1,'msg'=>'请先通过身份验证，再进行操作')));
                if($session_action['time']+15*60 < time()) exit(json_encode(array('error'=>1,'msg'=>'修改超时，请重新通过身份验证再进行操作')));
                $sreach_result = Db::name('user')->where("mobile='{$mobile}' and uid !={$user_info['uid']}")->find();
            }
            if($sreach_result) exit(json_encode(array('error'=>1,'msg'=>'该手机号已被绑定，请更换其他号码')));
            if(isPhone($mobile)==false) exit(json_encode(array('error'=>1,'msg'=>'请输入正确的手机号码')));

            //验证手机号和输入的验证码
            $res = Db::name('send_code')->where(array('uid'=>$user_info['uid'],'mobile'=>$mobile,'code'=>$mobile_code,'action'=>$action))->order(['id'=>'desc'])->find();

            if(!$res) exit(json_encode(array('error'=>1,'msg'=>'验证码不正确')));
            if($res['addtime']+15*60 < time()){
                exit(json_encode(array('error'=>1,'msg'=>'验证码已过期,请重新获取')));
            }

             $data = array(
                 'mobile'=>$mobile,
             );

            Db::name('user')->where(array('uid'=>$user_info['uid']))->update($data);
            session('action',null);

            $del_code = Db::name('send_code')->where(array('uid'=>$user_info['uid'],'action'=>$action))->select();

            foreach($del_code as $k=>$v){
                if($res['addtime']+24*60*60 < time()){
                    Db::name('send_code')->where(array('id'=>$v['id']))->delete();
                }
            }

            exit(json_encode(array('success'=>1,'msg'=>'操作成功')));

        }else{
            pc_404();
        }
    }


    //修改用户名
    public function change_username(){
        if(request()->isAjax()){
            $user_session = session('login');
            $change       = intval(input('post.change'));
            $username     = input('post.username');
            $user_info    = Db::name('user')->where(array('uid'=>$user_session['uid']))->find();

            if($user_info['username_status'] != 0) exit(json_encode(array('error'=>1,'msg'=>'此用户名已经更改过，不允许再次修改。')));

            if(isUsername($username)==false){
                exit(json_encode(array('error'=>1,'msg'=>'用户名应为2~12个字符（一个中文3个字符），不包含特殊字符')));
            }
            if(isUsername($username)==true){
                $user_info    = Db::name('user')->where(array('username'=>$username))->find();
                if($user_info) exit(json_encode(array('error'=>1,'msg'=>'此用户名已存在')));
            }

            //用户确认要修改用户名进行的操作
            if(isset($change) && $change==1){
                $res = Db::name('user')->where(array('uid'=>$user_session['uid']))->update(array('username'=>$username,'username_status'=>1));
                if($res){
                    session('login',array('uid'=>$user_session['uid'],'username'=>$username));
                    exit(json_encode(array('success'=>1,'msg'=>'修改成功')));
                }
            }

            exit(json_encode(array('success'=>1)));
        }else{
            pc_404();
        }
    }
    //绑定或者修改邮箱
    public function email()
    {
        if(request()->isAjax()){

            $user_info  = get_user_info();
            $email      = input('post.email');

            if(isEmail($email)==false) exit(json_encode(array('error'=>1,'msg'=>'请输入正确的邮箱')));
            if(empty($user_info['email'])){
                $where = "email='{$email}'";
                $dongzuo = '绑定';
                $action  = 3;
            }else{
                $where = "email='{$email}' and uid != {$user_info['uid']}";
                $dongzuo = '更换';
                $action  = 4;
            }

            if(Db::name('user')->where($where)->find()) exit(json_encode(array('error'=>1,'msg'=>'此邮箱已被绑定，请更换其他邮箱')));

            //绑定邮箱
            /**/
            $res = Db::name('send_code')->where(array('uid'=>$user_info['uid'],'email'=>$email,'action'=>$action))->order('id desc')->find();
            if($res && $res['addtime']+60 > time()) exit(json_encode(array('error'=>1,'msg'=>'邮件已经发出，60秒之内请勿重发')));
            $code = create_randomstr(50);
            $url  = url('start/index/bind_email')."?email=".base64_encode($email)."&code=$code";
            $email_s = substr_replace($email,'****',3,4);
            $content = <<<AAA
    <p>
    <br/>
</p>
<p style="line-height: 27.2px; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, 微软雅黑, 宋体, helvetica, &#39;Hiragino Sans GB&#39;; white-space: normal; padding-left: 33px;">
    亲爱的{$email_s}, 您好：
</p>
<p style="line-height: 23.8px; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, 微软雅黑, 宋体, helvetica, &#39;Hiragino Sans GB&#39;; font-size: 14px; white-space: normal; padding-top: 10px; padding-left: 33px;">
    您正在{$dongzuo}邮箱！请点击下面的链接完成操作：
</p>
<p style="line-height: 23.8px; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, 微软雅黑, 宋体, helvetica, &#39;Hiragino Sans GB&#39;; font-size: 14px; white-space: normal; padding-left: 33px;">
    <a href="{$url}" target="_blank" style="outline: none; cursor: pointer; color: rgb(83, 142, 219); word-wrap: break-word;">{$url}</a>
</p>
<p style="line-height: 23.8px; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, 微软雅黑, 宋体, helvetica, &#39;Hiragino Sans GB&#39;; font-size: 14px; white-space: normal; padding-left: 33px;">
    为了确保您的帐号安全，该链接仅24小时内访问有效，请勿直接回复该邮件。
</p>
<p style="line-height: 23.8px; color: rgb(51, 51, 51); text-indent:500px;font-family: &#39;Microsoft YaHei&#39;, 微软雅黑, 宋体, helvetica, &#39;Hiragino Sans GB&#39;; font-size: 14px; white-space: normal; padding-top: 20px; padding-left: 584px;">
    七里香
</p>
<p>
    <br/>
</p>
AAA;
            $title = "您正在申请{$dongzuo}邮箱，请查收邮件";
            $send_code = send_emailCode($email,$title,$content);
            if($send_code){
                $data = array(
                    'uid'     =>  $user_info['uid'],
                    'email'   =>  $email,
                    'code'    =>  $code,
                    'action'  =>  $action,
                    'type'    =>  2,
                    'addtime' =>  time(),
                );

                Db::name('send_code')->insert($data);
                exit(json_encode(array('success'=>1,'msg'=>'邮件已发送，请登录邮箱查收')));
            }
        }else{
            pc_404();
        }
    }
    //初始化或者修改密码
    public function password(){
        if(request()->isAjax()){
            $user_info      = get_user_info();
            $password       = input('post.password');

            if(empty($user_info['mobile']) && empty($user_info['email'])) exit(json_encode(array('error'=>1,'msg'=>'请先绑定手机或者邮箱再进行操作')));
            if(isPassword($password)==false) exit(json_encode(array('error'=>1,'msg'=>'密码长度应为6-16位，不包含特殊字符')));

            if($user_info['password']){
                $session_action = session('action');
                if(!$session_action) exit(json_encode(array('error'=>1,'msg'=>'请先通过身份验证，再进行操作')));
                if($session_action['time']+15*60 < time()) exit(json_encode(array('error'=>1,'msg'=>'修改超时，请重新通过身份验证再进行操作')));
            }
            $pwd = password($password);
            $data = array(
                'password'=>$pwd['password'],
                'encrypt'=>$pwd['encrypt'],
            );
            Db::name('user')->where(array('uid'=>$user_info['uid']))->update($data);
            session('action',null);
            exit(json_encode(array('success'=>1,'msg'=>'密码设置成功')));

        }else{
            pc_404();
        }
    }

    //绑定第三方账号--页面
    public function bind_account(){
        $this->get_nav_menu();
        $userinfo = get_user_info();
        $this->assign('userinfo',$userinfo);
        return $this->fetch('index/user/bind_account');
    }


    //显示页面微信二维码--或者检查是否绑定了微信。绑定微信在 weixin/index/update_userweixin_info  操作完成
    public function weixin_bind()
    {
        //检查是否绑定了微信
        if(request()->isAjax()){
            $bind_weixin = input('post.bind_weixin/d');
            if($bind_weixin != 1) exit(json_encode(array('error'=>1,'msg'=>'非法的请求来源')));
            $user_info = get_user_info();
            if($user_info['weixin_openid'] or $user_info['weixin_unionid']){
                exit(json_encode(array('success'=>1,'msg'=>'微信绑定成功')));
            }else{
                exit(json_encode(array('error'=>1)));
            }

        }else{
            //显示页面微信二维码
            return $this->fetch('index/user/weixin_bind');
        }
    }

}