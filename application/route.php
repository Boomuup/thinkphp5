<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    /*
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    */
     'start/index/get_geetest_code'    => 'start/index/get_geetest_code',
     'start/index/check_geetest_code'  => 'start/index/check_geetest_code',

     'start/[:name]'  => ['start/index/index',['ext'=>'html','method'=>'get'],['name'=>'[A-Za-z0-9]+']],
     'article/[:id]'  => ['start/index/article',['ext'=>'html','method'=>'get'],['id'=>'[A-Za-z0-9]+']],
     'user/[:id]'     => ['start/user/index',['ext'=>'html','method'=>'get'],['id'=>'[A-Za-z0-9]+']],
     'security/[:id]' => ['start/user/security',['ext'=>'html','method'=>'get'],['id'=>'[A-Za-z0-9]+']],
     'qq_login'       => ['start/index/qq_login',['ext'=>'html','method'=>'get']],
     'qqCallback'     => ['start/index/qqCallback',['ext'=>'html','method'=>'get']],

    //'admin/login'     => ['admin/login/login',['ext'=>'html','method'=>'get']],

];
