<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
class System extends Common
{
    // 初始化
    protected function _initialize(){

    }

    //系统信息
    public function system_info(){
        $info = session('admin');
        //mysql_connect('localhost','root','root');
        $sysinfo = array();
        $sysinfo['HTTP_HOST']           = $_SERVER['HTTP_HOST'];//域名
        $sysinfo['SERVER_ADDR']         = $_SERVER['SERVER_ADDR'];//服务器端ip
        $sysinfo['SERVER_PORT']         = $_SERVER['SERVER_PORT'];//端口
        $sysinfo['REMOTE_ADDR']         = get_ip();//客户端ip
        $sysinfo['DOCUMENT_ROOT']       = $_SERVER['DOCUMENT_ROOT'];//路径
        $sysinfo['TIME']                = date('Y-m-d H:i:s');//时间
        $sysinfo['PHP_VERSION']         = PHP_VERSION;//php版本
        $sysinfo['MYSQL']               = php_uname(); //获取系统类型及版本号;//MYSQL版本
        $sysinfo['SERVER_NAME']         = $_SERVER['SERVER_NAME'];//主机名
        $sysinfo['PHP_OS']              = PHP_OS;//服务器操作系统
        $sysinfo['APACHE']              = $_SERVER['SERVER_SOFTWARE'];//apache版本

        $res = Db::name('admin_log')->where(array('uid'=>$info['uid'],'type'=>1))->order(['lid'=>'desc'])->limit(2)->select();
        unset($res[0]);

        $login_count = Db::name('admin_log')->where(array('type'=>1))->count();
        $place = get_ipCity($res[1]['logstr']);

        $sysinfo['last_login_count']    = $login_count;//登录次数
        $sysinfo['last_login_ip']       = $res[1]['logstr'];//上次登录ip
        $sysinfo['last_login_city']     = is_array($place)?implode(' ',$place):$place;//上次登录城市
        $sysinfo['last_login_time']     = date('Y年m月d日 H:i:s',$res[1]['addtime']);//上次登录时间

        $this->assign('sysinfo',$sysinfo);
        return $this->fetch('system-info');
    }

    //系统设置
    public function system_base(){

        return $this->fetch('system-base');
    }

    //栏目管理
    public function system_category(){

        return $this->fetch('system-category');
    }

    //数据字典
    public function system_data(){

        return $this->fetch('system-data');
    }

    //屏蔽词
    public function system_shielding(){

        return $this->fetch('system-shielding');
    }

    //系统日志
    public function system_log(){

        return $this->fetch('system-log');
    }


    //添加
    public function nav_add(){
        if($_POST){
            if(get_admin_ugid()==false) $this->error('没有权限进行操作');
            $belong_to   = input('post.belong_to');
            $nav_name    = input('post.nav_name');
            $nav_url     = input('post.nav_url');
            $footer_url  = input('post.nav_footer');
            $footer_name = input('post.footer_name');
            $is_show     = input('post.is_show');
            if(!$nav_name) $this->error('没有权限进行操作');
            $data = array();
            $data['nav_name']    = $nav_name;
            $data['nav_url']     = $nav_url;
            $data['footer_url']  = $footer_url;
            $data['footer_name'] = $footer_name;
            $data['is_show']     = $is_show;
            $data['belong_to']  = $belong_to;
            $data['addtime']     = time();
            $res = Db::name('nav')->insert($data);
            $url = url('admin/nav/nav_list').'?belong_to='.$belong_to;
            if($res) $this->success('数据添加成功！',$url);
        }else{
            $parent_nav = Db::name('nav')->where(array('pid'=>0))->select();
            $this->assign('parent_nav',$parent_nav);
            return $this->fetch('nav_add');
        }
    }


    //编辑
    public function nav_edit(){
        if($_POST){
            if(get_admin_ugid()==false) $this->error('没有权限进行操作');
            $id          = input('post.id');
            $belong_to   = input('post.belong_to');
            $nav_name    = input('post.nav_name');
            $nav_url     = input('post.nav_url');
            $footer_url  = input('post.nav_footer');
            $footer_name = input('post.footer_name');
            $is_show     = input('post.is_show');
            $data = array();
            $data['nav_name']    = $nav_name;
            $data['nav_url']     = $nav_url;
            $data['footer_url']  = $footer_url;
            $data['footer_name'] = $footer_name;
            $data['is_show']     = $is_show;
            $data['belong_to']  = $belong_to;
            $data['addtime']     = time();

            $res = Db::name('nav')->where(array('id'=>$id))->update($data);
            $url = url('admin/nav/nav_list').'?belong_to='.$belong_to;
            if($res) $this->success('修改成功！',$url);
        }else{
            $id = input('get.id');
            $data = Db::name('nav')->where(array('id'=>$id))->find();

            $parent_nav = Db::name('nav')->where(array('pid'=>0))->select();
            $this->assign('parent_nav',$parent_nav);
            $this->assign('data',$data);
            return $this->fetch('nav_add');
        }
    }


    public function delete()
    {
        if($_GET){
            if(get_admin_ugid()==false) exit(json_encode(array('status'=>0,'msg'=>'没有权限进行操作')));
            $id = intval(input('get.id'));
            $res = Db::name('nav')->where(array('id'=>$id))->delete();
            if($res){
                exit(json_encode(array('status'=>1,'msg'=>'删除成功！')));
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'删除失败！')));
            }

        }
    }

}