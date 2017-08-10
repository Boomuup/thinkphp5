<?php
namespace app\mobile\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
class Index extends Controller
{
    // 初始化
    protected function _initialize()
    {
      
    }
    //空操作
    public function _empty(){
       return $this->index();
    }


    //首页
    public function index()
    {
        $request = Request::instance();
        $request->param();
        input();

        $info = Db::name('user')->find();
        $this->assign('info', $info);
        return $this->fetch('index');
    }
    //个人档案
    public function my_info()
    {
        
        return $this->fetch('my_info');
    }
    //路上风景
    public function my_diary()
    {
        
        return $this->fetch('my_diary');
    }
    //一些话要说
    public function my_word()
    {
        
        return $this->fetch('my_word');
    }
}
