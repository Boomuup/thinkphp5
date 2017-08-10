<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;

class Admin extends Common
{
    // 初始化
    protected function _initialize(){

    }

    //列表
    public function nav_list(){
        $data  = Db::name('nav')->order(['belong_to'=>'asc','id'=>'asc'])->paginate(2);
        $page  = $data->render();

        $count = Db::name('nav')->count();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('count',$count);
        return $this->fetch('nav_list');
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
            $data = array();
            $data['nav_name']    = $nav_name;
            $data['nav_url']     = $nav_url;
            $data['footer_url']  = $footer_url;
            $data['footer_name'] = $footer_name;
            $data['is_show']     = $is_show;
            $data['belong_to']  = $belong_to;
            $data['addtime']     = time();
            $res = Db::name('nav')->insert($data);
            if($res) $this->success('数据添加成功！');
        }else{
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


    //登录日志列表
    public function login_log_list(){
        $data  = Db::name('admin_log')->order(['uid'=>'asc','lid'=>'desc'])->paginate(10);
        $page  = $data->render();


        $count = Db::name('admin_log')->count();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('count',$count);
        return $this->fetch('admin_log_list');
    }

    //角色管理
    public function admin_role()
    {
        return $this->fetch('admin-role');
    }

    //权限管理
    public function admin_permission()
    {
        return $this->fetch('admin-permission');
    }

    //管理员列表
    public function admin_list()
    {
        return $this->fetch('admin-list');
    }

}