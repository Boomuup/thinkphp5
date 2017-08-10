<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
class Login extends Controller
{
    // 初始化
    protected function _initialize(){


    }
    //空操作
    public function _empty(){
        if(session('admin')){
            $url = 'admin/index/index';
        }else{
            $url = 'admin/login/login';
        }
        header('location:'.url($url));
    }
    //后台登录页面
    public function login(){
      if($_POST){
          $admin_name  = input('post.admin_name');
          $password    = input('post.admin_password');
          $code        = input('post.code');

          //if(chcek_slide_code($_POST)==false)   $this->error('请先把验证码拖动到正确位置','','',1);
          if(!$admin_name)                $this->error('请输入管理用户名');
          if(!$password)                  $this->error('请输入登录密码');
          //if(!$code)                $this->error('请输入验证码');
          //if(!captcha_check($code)) $this->error('验证码不正确');

          $res = Db::name('admin')->where(array('username'=>$admin_name))->find();
          if(!$res) $this->error('此管理用户不存在');
          $password = password($password,$res['encrypt']);

          $res = Db::name('admin')->where(array('username'=>$admin_name,'password'=>$password))->find();
          if(!$res) $this->error('用户名或密码不正确');
          session('admin',array('uid'=>$res['userid'],'username'=>$res['username']));
          Db::name('admin_log')->insert(array('uid'=>$res['userid'],'logstr'=>get_ip(),'addtime'=>time(),'type'=>1));
          $this->success('登录成功！','admin/index/index','',2);
      }else{
          return $this->fetch('login');
      }
    }
    //后台退出登录
    public function login_out(){
        $info = session('admin');
        if(!$info) $this->error('非法访问','admin/login/login','',2);
        $res1 = Db::name('admin')->where(array('userid'=>$info['uid']))->update(array('lastloginip'=>get_ip(),'lastlogintime'=>time()));
        $res2 = Db::name('admin_log')->insert(array('uid'=>$info['uid'],'logstr'=>get_ip(),'addtime'=>time(),'type'=>2));
        if($res1 && $res2 && session('admin',null)==null){
            $this->success('退出成功！','admin/login/login','',2);
        }
    }
    //生成验证码
    public function creat_code(){
        create_slide_code();
    }

    // geetest ajax 验证
    public function geetest_ajax_check(){
        if($_POST){
            $data=input('post.');
            if(chcek_slide_code($data)){
                exit(json_encode(array('status'=>1,'msg'=>'真棒，验证成功通过啦~')));
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'要先把验证码滑动到正确位置噢~')));
            }
        }
    }

}