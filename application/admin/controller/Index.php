<?php
namespace app\admin\controller;


class Index extends Common
{

    // 初始化
    protected function _initialize()
    {

    }


    //首页
    public function index()
    {

       return $this->fetch('index');
    }

}