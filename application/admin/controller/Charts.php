<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Charts extends Common
{

    // 初始化
    protected function _initialize()
    {

    }

    //折线图
    public function line_charts()
    {

       return $this->fetch('charts-1');
    }

    //时间轴折线图
    public function time_line_charts()
    {

        return $this->fetch('charts-2');
    }

    //区域图
    public function area_charts()
    {

        return $this->fetch('charts-3');
    }

    //柱状图
    public function zhu_charts()
    {

        return $this->fetch('charts-4');
    }

    //饼状图
    public function cake_charts()
    {

        return $this->fetch('charts-5');
    }

    //3D柱状图
    public function zhu_charts_3d()
    {

        return $this->fetch('charts-6');
    }

    //3D饼状图
    public function cake_charts_3d()
    {

        return $this->fetch('charts-7');
    }

}