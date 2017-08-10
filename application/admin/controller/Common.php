<?php
namespace app\admin\controller;
use think\Controller;
class Common extends Controller
{
    // 初始化
     public function __construct(){
         parent::__construct();
         if(!session('admin')){
          $this->error('请先登录！','admin/login/login','',2);
         }
    }

}