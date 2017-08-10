<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\index\user\security.html";i:1490768119;s:74:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\user\_meta.html";i:1490768125;s:67:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\nav.html";i:1487855340;s:78:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\user\left_menu.html";i:1490767178;}*/ ?>
﻿<!DOCTYPE html>
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


<title>账号设置</title>
</head>
<body class="body">

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
            <h2>账号安全</h2>
            <div class="security">
                <ul>
                    <li>
                        <?php if($user_info['mobile']){?>
                            <label>绑定手机</label>
                            <span><?php echo substr_replace($user_info['mobile'],'****',3,4);?></span>
                            <a id="changePhone" href="javascript:;">修改手机号</a>
                        <?php }else{?>
                            <label>绑定手机</label>
                            <span>没有绑定 </span>
                            <a id="setPhone" href="javascript:;">绑定手机</a>
                        <?php }?>
                    </li>
                    <li>
                        <?php if($user_info['email']){?>
                            <label>绑定邮箱</label>
                            <span><?php echo substr_replace($user_info['email'],'****',3,4);?></span>
                            <a id="changeEmail" href="javascript:;">修改邮箱</a>
                        <?php }else{?>
                            <label>绑定邮箱</label>
                            <span>没有绑定 </span>
                            <a id="setEmail" href="javascript:;">绑定邮箱</a>
                        <?php }?>
                    </li>

                    <li>
                        <?php if($user_info['password']){?>
                            <label>设置密码</label>
                            <span>******** <span class="ora">( 设置密码前，请先绑定邮箱或手机 )</span></span>
                            <!-- 没绑定邮箱或手机不显示设定密码 -->
                            <a id="changePwd" href="javascript:;">修改登录密码</a>
                        <?php }else{?>
                            <label>设置密码</label>
                            <span>******** <span class="ora">( 设置密码前，请先绑定邮箱或手机 )</span></span>
                            <a id="setPwd" href="javascript:;">设置登录密码</a>
                        <?php }?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 验证身份 -->
    <div class="popup" id="verify-type-area" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; min-height: 50px; margin:0 0 0 -180px;display: none;">
        <div class="wrap">
            <div class="hd">
                <h2>验证身份</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting">
                    <p class="note">为了保证你的帐号安全，请验证身份。
                        <br/>验证成功后进行下一步操作。</p>
                    <fieldset>
                        <div class="form-item">
                            <div class="item-cont w-lg">
                                <select class="select w-lg" id="verify-type" name="verify-type">
                                    <?php if($user_info['password']){?>
                                        <option value="0">通过登录密码验证身份</option>
                                    <?php }if($user_info['email']){?>
                                        <option value="2">通过邮箱<?php echo substr_replace($user_info['email'],'****',3,4);?>验证</option>
                                    <?php }if($user_info['mobile']){?>
                                        <option value="1">通过手机号<?php echo substr_replace($user_info['mobile'],'****',3,4);?>验证</option>
                                    <?php }?>
								</select>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont w-lg" id="verifyPwd">
                                <input class="txt w-lg" id="ver-pwd" type="password" />
                            </div>
                            <div id="verifyOther">
                                <div class="item-label">
                                    <label>验证码</label>
                                </div>
                                <div class="item-cont no-right">
                                    <input class="txt w-xs auth-code" type='text' />
                                    <button type="button" class="get-code orange-btn" onclick="get_code(this);">发送验证码</button>
                                </div>
                            </div>

                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" id="verifyIdBtn" class="btn green-btn lg" value="验证身份" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- /bd -->
        </div>
    </div>
    <!-- /验证身份 -->
    <!-- 设定初始密码 -->
    <div class="popup" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; min-height: 50px; margin:0 0 0 -180px;display: none; " id="setPwd-area">
        <div class="wrap">
            <div class="hd">
                <h2>初始设定密码</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting">
                    <fieldset>
                        <div class="form-item">
                            <div class="item-label">
                                <label>密码</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt sm pw1" type='password' maxlength="17" placeholder="输入你的初始密码" onkeyup="pwd_level(this)" />
                                <div class="safety pwd-strong">
                                    <span class="level-1" level="1">弱</span>
                                    <span class="level-2" level="2">中</span>
                                    <span class="level-3" level="3">强</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-label">
                                <label>确认密码</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt sm pw2"  type='password' maxlength="17" placeholder="确认密码" />
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" class="btn green-btn lg initPwdBtn" onclick="changePwd(this);" value="设定密码" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- /设定初始密码 -->
    <!-- 修改密码 -->
    <div class="popup" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; min-height: 50px; margin:0 0 0 -180px;display: none; " id="changePwd-area">
        <div class="wrap">
            <div class="hd">
                <h2>修改密码</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting">
                    <fieldset>
                        <div class="form-item">
                            <div class="item-label">
                                <label>新密码</label>
                            </div>
                            <div class="item-cont">
                                <input type="password" class="txt sm pw1" maxlength="17" onkeyup="pwd_level(this)" />
                                <div class="safety pwd-strong">
                                    <span class="level-1" level="1">弱</span>
                                    <span class="level-2" level="2">中</span>
                                    <span class="level-3" level="3">强</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-label">
                                <label>确认密码</label>
                            </div>
                            <div class="item-cont no-right">
                                <input type="password" class="txt sm pw2" maxlength="17" />
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" class="btn green-btn lg changepw-btn" onclick="changePwd(this);" value="确认修改" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- /修改密码 -->
    <!-- 绑定邮箱 -->
    <div class="popup" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; min-height: 50px; margin:0 0 0 -180px;display: none;" id="set-email">
        <div class="wrap">
            <div class="hd">
                <h2>绑定邮箱</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting h-auto">
                    <fieldset>
                        <div class="form-item">
                            <div class="item-label">
                                <label>邮箱</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt sm init-email" />
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" class="btn green-btn lg set-email-btn" onclick="changeEmail(this,3);" value="确认绑定" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- /绑定邮箱 -->
    <!-- 修改邮箱 -->
    <div class="popup" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; min-height: 241px; margin:0 0 0 -180px;display: none;" id="change-email">
        <div class="wrap">
            <div class="hd">
                <h2>修改邮箱</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting h-auto">
                    <fieldset>
                        <div class="form-item">
                            <div class="item-label">
                                <label>邮箱</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt sm new-email" />
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" class="btn green-btn lg change-email-btn" onclick="changeEmail(this,4);" value="确认修改" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- /修改邮箱 -->
    <!-- 绑定手机号 data-state = 'alter/bind' -->
    <div class="popup" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; height: 301px; margin:0 0 0 -180px;display: none;" id="set-phone-area" data-state='bind'>
        <div class="wrap">
            <div class="hd">
                <h2>绑定手机号</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting h-auto">
                    <fieldset>
                        <div class="form-item">
                            <div class="item-label">
                                <label>手机号</label>
                                <!-- <label>新手机号</label> -->
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt sm init-phone" />
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-label">
                                <label>验证码</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt w-xs auth-code" type='text' />
                                <button type="button" href="javascript:;" class="get-code orange-btn" onclick="get_code(this,1);">发送验证码</button>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" class="btn green-btn lg set-phone-btn" onclick="changePhone(this,1);" value="确认绑定" />
                                <!-- <input type="button" class="btn btn-primary sm p-sm set-phone-btn" value="确认修改" /> -->
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <!-- /绑定手机号 -->
    <!-- 修改手机号 -->
    <div class="popup" style="position:fixed;left:50%;top:100px;z-index:10001;width: 360px; height: 301px; margin:0 0 0 -180px;display: none;" id="change-phone" data-state='alter'>
        <div class="wrap">
            <div class="hd">
                <h2>修改手机号</h2>
                <i class="Hui-iconfont close popclose">&#xe6a6;</i>
            </div>
            <div class="bd">
                <form class="form form-setting h-auto">
                    <fieldset>
                        <div class="form-item">
                            <div class="item-label">
                                <label>新手机号</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt sm new-phone" />
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-label">
                                <label>验证码</label>
                            </div>
                            <div class="item-cont no-right">
                                <input class="txt w-xs auth-code" type='text' />
                                <!--<p style="font-size: 10px;color: #ccc;line-height: 16px;">(注：请填写验证身份时的收到的验证码)</p>-->
                                <button type="button" class="get-code orange-btn" onclick="get_code(this,2);">发送验证码</button>
                            </div>
                        </div>
                        <div class="form-item">
                            <div class="item-cont">
                                <input type="button" class="btn green-btn lg change-phone-btn" onclick="changePhone(this,2);" value="确认修改" />
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- /修改手机号 -->

    <!-- /解除绑定2 -->
</body>

<script src="/public/static/start/js/user/securty.js"></script>
</html>
