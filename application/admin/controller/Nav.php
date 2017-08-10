<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
class Nav extends Common
{
    // 初始化
    protected function _initialize(){

    }

    //列表
    public function nav_list(){
        $data  = Db::name('nav')->order(['belong_to'=>'asc','id'=>'asc'])->paginate(10);
        $page  = $data->render();
        $count = Db::name('nav')->count();
        $count_page = ceil($count/10);//分页总页数

        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('count',$count);
        $this->assign('count_page',$count_page);
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