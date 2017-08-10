<?php
namespace app\home\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
class Error extends Controller
{
    // 初始化
    protected function _initialize()
    {
      
    }
    //空操作
    public function _empty($name='333'){
       return $name;
    }


    //首页
    public function index()
    {
       
    }
 
}
