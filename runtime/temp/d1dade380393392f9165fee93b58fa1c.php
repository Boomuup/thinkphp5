<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\index\user\bind_account.html";i:1490780175;s:74:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\user\_meta.html";i:1490768125;s:67:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\nav.html";i:1487855340;s:78:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\user\left_menu.html";i:1490767178;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <!--H-ui图标-->
    <link rel="stylesheet" href="/public/static/admin/static/h-ui/css/H-ui.min.css"/>
    <link rel="stylesheet" href="/public/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css"/>

    <!--头部-->
    <link rel="stylesheet" href="/public/static/start/css/uicn.v1.css">
    <link rel="stylesheet" href="/public/static/start/css/header.v1.css">
    <link rel="stylesheet" href="/public/static/start/css/footer.v1.css">

    <link rel="stylesheet" href="/public/static/start/css/user/user.min.css">
    <link rel="stylesheet" href="/public/static/start/css/user/security.min.css">
    <link rel="stylesheet" href="/public/static/start/css/zhuce.css">


    <script type="text/javascript" src="/public/static/public/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/public/static/public/js/js_msg.js"></script>
    <script type="text/javascript" src="/public/static/admin/static/h-ui/js/H-ui.min.js"></script>
    <script type="text/javascript" src="/public/static/public/layer_pc/layer.js"></script>
    <script type="text/javascript" src="/public/static/start/js/header.v1.js"></script>


<title>绑定第三方账号</title>
<script src="/public/static/start/js/user/bind_account.js"></script>
</head>
<body>

<div class="nav-div">
    <div class="bg-white bg-white-nav">
        <div class="wpn cl bg-white">
            <ul class="nav-hd cl">
                <?php foreach($nav_data as $key=>$val){ ?>
                    <li>
                        <a href="<?php if($val['children'] && $key+1==count($nav_data)){echo 'javascript:;';}else{echo url($val['nav_url']);}?>" target="_self"><?php echo $val['nav_name'];?><i class="<?php echo $key+1==count($nav_data)?'icon-down':'';?>"></i>	</a>
                        <?php if($val['children']){?>
                            <div class="subnav-hd cl">
                                <ul class="subnav-ct-hd">
                                    <?php foreach($val['children'] as $k=>$v){ ?>
                                        <li>
                                            <a href="<?php echo url($v['nav_url']);?>" target="_blank"><?php echo $v['nav_name'];?></a>
                                        </li>
                                    <?php }?>
                                </ul>
                            </div>
                        <?php }?>
                    </li>
                <?php }?>
            </ul>
            <div class="y cl">
                <div class="search-hd cl">
                    <form action="#" method="get" id="searchForm">
                        <div class="search-status">
                            <div class="search-filter">
                                <a href="javascript:;"><span class="tit">作品</span><i class="icon-down"></i></a>
                                <ul class="options">
                                    <li><a href="javascript:;" data-rel="project">作品</a></li>
                                    <li><a href="javascript:;" data-rel="experience">文章</a></li>
                                    <li><a href="javascript:;" data-rel="source">源文件</a></li>
                                    <li><a href="javascript:;" data-rel="designer">设计师</a></li>
                                    <li><a href="javascript:;" data-rel="inspiration">灵感</a></li>
                                    <li><a href="javascript:;" data-rel="talk">话题</a></li>
                                </ul>
                            </div>
                            <div class="search-select">
                                <input type="text" class="search-val" placeholder="请输入您要查找的内容" autocomplete="off" value="" name="keywords" id="keywords"/>
                                <ul class="options">
                                    <li><a href="javascript:;">icon</a></li>
                                    <li><a href="javascript:;">春节</a></li>
                                    <li><a href="javascript:;">app</a></li>
                                    <li><a href="javascript:;">作品集</a></li>
                                    <li><a href="javascript:;">AE</a></li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" name="type" value='project' id="sType"/>
                        <a class="Hui-iconfont search-hd-btn" href="javascript:;">&#xe683;</a>
                    </form>
                </div>
                <?php if($login = session('login')){?>
                        <ul class="quick-hd cl">
                            <li class="quick-item">
                                <a class="quick-toggle" href="javascript:;">
                                    <img style="width: 53px;height: 53px;position: absolute;pointer-events: none;left: 4px;top: 4px;" src="<?php echo $user_info['logo'] ? $user_info['logo'] : '/public/static/public/face/'.$user_info['face'].'.png';?>"/>
                                </a>
                                <ul class="quick-menu quick-list">
                                    <li><a href="<?php echo url('start/user/index');?>">个人资料</a></li>
                                    <li><a href="">我的收藏</a></li>
                                    <li><a href="">我的简历</a></li>
                                    <li><a href="">修改资料</a></li>
                                    <li><a href="javascript:;" onclick="login_out(this);">退出登录</a></li>
                                </ul>
                            </li>
                            <div class="clean_float"></div>
                        </ul>

                <?php }else{?>
                    <div class="login-hd">
                        <a href="javascript:;" class="denglu_btn"><i class="Hui-iconfont">&#xe60d;</i> 登录</a>
                    </div>
                <?php }?>
            </div>
        </div><!-- wpn -->
    </div>
    <!--<a href="javascript:;" id="get-ip" style="position: absolute;top:28px;right: 50px;">位置：<?php echo get_ipCity() ? implode(' ',get_ipCity()) : '';?></a>-->
</div>
<div class="wrapper">
    <nav class="leftnav" id="leftNav">
    <ul>
        <li><a href="<?php echo url('start/user/index');?>" <?php if(A_NAME=='index'){?>class="active"<?php }?> >个人资料</a></li>
        <li>
            <a href="" class="">认证学员</a>
            <i class="nopro-icon"></i>
            <span></span>
        </li>
        <li><a href="" class="">我的VIP</a></li>
        <li><a href="" class="">我的极客币</a></li>
        <li><a href="" class="">我的订单</a></li>
        <li><a href="<?php echo url('start/user/security');?>" <?php if(A_NAME=='security'){?>class="active"<?php }?> >账号安全</a></li>
        <li><a href="" class="">消息设置</a></li>
        <li><a href="<?php echo url('start/user/bind_account');?>" class="">绑定第三方账号</a></li>
    </ul>
</nav>
    <div class="main">
        <h2>绑定第三方账号</h2>
        <div class="sns">
            <ul class="multi-line third">
                <li>
                    <label><i class="Hui-iconfont" style="font-size: 35px;color: #00a0e9">&#xe67b;</i></label>
                    <span>QQ <span class="ora">( <?php echo $userinfo['qq_openid'] ? '已绑定':'未绑定' ;?> )</span></span>
                    <?php if($userinfo['qq_openid']){?>
                        <a id="unbindQQ" class="unbindBtn" href="javascript:;" >解除绑定</a>
                    <?php }else{?>
                        <a id="bindQQ" class="unbindBtn" href="javascript:;" >绑定QQ账号</a>
                    <?php }?>
                </li>
                <li>
                    <label><i class="Hui-iconfont" style="font-size: 35px;color: #00a65a;">&#xe694;</i></label>
                    <span>微信账号 <span class="ora">( <?php echo $userinfo['winxin_openid'] && $userinfo['weixin_unionid'] ? '已绑定':'未绑定' ;?> )</span></span>
                    <?php if($userinfo['winxin_openid'] && $userinfo['weixin_unionid']){?>
                        <a id="unbindWeixin" class="unbindBtn" href="javascript:;" >解除绑定</a>
                    <?php }else{?>
                        <input type="hidden" class="weixin-bind-url" value="<?php echo url('start/user/weixin_bind');?>">
                        <a id="bindWeixin" onclick="weixin_account(this,1);" class="unbindBtn" href="javascript:;" >绑定微信账号</a>
                    <?php }?>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>