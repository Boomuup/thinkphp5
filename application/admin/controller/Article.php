<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
class Article extends Common
{
    // 初始化
    protected function _initialize(){

    }

    //文章分类列表
    public function article_category(){
        $data  = Db::name('article_category')->order(['order_list'=>'asc','catid'=>'asc'])->paginate(2);
        $page  = $data->render();
        $count = Db::name('article_category')->count();
        $count_page = ceil($count/2);//分页总页数

        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('count',$count);
        $this->assign('count_page',$count_page);
        return $this->fetch('article-category-list');
    }

    //添加文章分类列表
    public function article_category_add(){
        if($_POST){
            if(get_admin_ugid()==false) $this->error('没有权限进行操作');
            $pid         = input('post.pid');
            $name        = input('post.name');
            $keyword     = input('post.keyword');
            $description = input('post.description');
            $is_show     = input('post.is_show');

            if(!$name) $this->error('文章分类名不能为空');
            if(Db::name('article_category')->where(array('name'=>$name))->find()) $this->error('此文章分类已存在');

            $data = array();
            $data['pid']         = $pid?$pid:0;
            $data['name']        = $name;
            $data['keyword']     = $keyword;
            $data['description'] = $description;
            $data['is_show']     = $is_show;
            $data['addtime']     = time();
            $res = Db::name('article_category')->insert($data);

            if($res) $this->success('文章分类添加成功！',url('admin/article/article_category'));
        }else{
            $category = Db::name('article_category')->where(array('pid'=>0))->select();
            $this->assign('category',$category);
            return $this->fetch('article-category-add');
        }
    }

    //编辑文章分类列表
    public function article_category_edit(){
        if($_POST){
            if(get_admin_ugid()==false) $this->error('没有权限进行操作');
            $catid       = input('post.catid');
            $pid         = input('post.pid');
            $name        = input('post.name');
            $keyword     = input('post.keyword');
            $description = input('post.description');
            $is_show     = input('post.is_show');
            $page        = input('post.page');

            if(!$name) $this->error('文章分类名不能为空');
            if(Db::name('article_category')->where("name='{$name}' and catid != $catid")->find()) $this->error('此文章分类已存在');

            $data = array();
            $data['pid']         = $pid?$pid:0;
            $data['name']        = $name;
            $data['keyword']     = $keyword;
            $data['description'] = $description;
            $data['is_show']     = $is_show;
            $res = Db::name('article_category')->where(array('catid'=>$catid))->update($data);

            if($res) exit(json_encode(array('success'=>1,'msg'=>'编辑成功！')));
        }else{
            $catid     = input('get.catid');
            $edit_data = Db::name('article_category')->where(array('catid'=>$catid))->find();

            $category = Db::name('article_category')->where(array('pid'=>0))->select();
            $this->assign('edit_data',$edit_data);
            $this->assign('category',$category);
            return $this->fetch('article-category-add');
        }
    }

    //删除文章分类
    public function article_category_del(){
        if($_POST){
            if(get_admin_ugid()==false) exit(json_encode(array('error'=>1,'msg'=>'没有权限进行操作！')));
            $catid  = input('post.catid/a');
            foreach($catid as $v){
                $del_article = Db::name('article')->where(array('catid'=>$v))->select();
                if($del_article){
                    foreach($del_article as $vv){
                        Db::name('article')->where(array('id'=>$vv['id']))->delete();
                    }
                }
                Db::name('article_category')->where(array('catid'=>$v))->delete();
            }
            exit(json_encode(array('success'=>1,'msg'=>'删除成功！')));
        }
    }

    //文章列表
    public function article_list()
    {
        $data  = Db::name('article')->order(['order_list'=>'asc','catid'=>'desc'])->paginate(2);
        $page  = $data->render();
        $count = Db::name('article')->count();
        $count_page = ceil($count/2);//分页总页数

        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('count',$count);
        $this->assign('count_page',$count_page);
        return $this->fetch('article-list');
    }

    //添加
    public function add(){
        if($_POST){
            if(get_admin_ugid()==false) $this->error('没有权限进行操作');
            $insert_data = input('post.');
            htmlspecialchars($insert_data['content']);
            if($insert_data['reply_time_s']) strtotime($insert_data['reply_time_s']);
            if($insert_data['reply_time_e']) strtotime($insert_data['reply_time_e']);
            $insert_data['addtime'] = time();

            /**/
            $field = Db::query("SHOW COLUMNS FROM v5_article");
            foreach($field as $k=>$v){
                $db_field[] = $v['Field'];
            }

            foreach($insert_data as $k=>$v){
                if(!in_array($k,$db_field)){
                    unset($insert_data[$k]);
                }
            }
            $res = Db::name('article')->insert($insert_data);
            if($res) $this->success('数据添加成功！',url('admin/article/article_list'));
        }else{
            $category = Db::name('article_category')->where(array('pid'=>0))->select();
            $this->assign('category',$category);
            return $this->fetch('article-add');
        }
    }


    //编辑
    public function edit(){
        if($_POST){
            if(get_admin_ugid()==false) $this->error('没有权限进行操作');
            $id          = input('post.id');
            $update_data = input('post.');
            htmlspecialchars($update_data['content']);
            if($update_data['reply_time_s']) strtotime($update_data['reply_time_s']);
            if($update_data['reply_time_e']) strtotime($update_data['reply_time_e']);
            $update_data['update'] = time();

            /**/
            $field = Db::query("SHOW COLUMNS FROM v5_article");
            foreach($field as $k=>$v){
                $db_field[] = $v['Field'];
            }

            foreach($update_data as $k=>$v){
                if(!in_array($k,$db_field)){
                    unset($update_data[$k]);
                }
            }

            $res = Db::name('article')->where(array('id'=>$id))->update($update_data);
            if($res) $this->success('修改成功！',url('admin/article/article_list'));
        }else{
            $id = input('get.id');
            $data = Db::name('article')->where(array('id'=>$id))->find();

            $category = Db::name('article_category')->where(array('pid'=>0))->select();
            $this->assign('category',$category);
            $this->assign('data',$data);
            return $this->fetch('article-add');
        }
    }


    public function delete()
    {
        if($_POST){
            if(get_admin_ugid()==false) exit(json_encode(array('status'=>0,'msg'=>'没有权限进行操作')));
            $id   = input('post.id/a');
            foreach ($id as $v){
                $res = Db::name('article')->where(array('id'=>$v))->delete();
            }

            if($res)
                exit(json_encode(array('success'=>1,'msg'=>'删除成功！')));
            else
                exit(json_encode(array('success'=>0,'msg'=>'删除失败！')));
        }
    }

}