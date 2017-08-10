<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:72:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\index\user\user.html";i:1490766501;s:74:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\user\_meta.html";i:1490768125;s:67:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\nav.html";i:1487855340;s:78:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\user\left_menu.html";i:1490767178;}*/ ?>
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


    <script type="text/javascript" src="/public/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <title>七里香-个人中心</title>
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

<div class="wrapper cf">
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
            <h2>个人资料</h2>
            <section class="basic">
                <h3>基本信息</h3>
                <form id="userinfoForm" method="post">
                    <ul>
                        <li>
                            <label for="">头像</label>
                            <div class="inputbox">
                                <div class="portrait-box"><img class="headpic" src="<?php echo $user_info['logo'] ? $user_info['logo'] : '/public/static/public/face/'.$user_info['face'].'.png';?>">
                                    <p class="change" id="setPhoto"><span>修改</span></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <label for="uname">用户名</label>
                            <div class="inputbox">
                                <span class="username"><?php echo $user_info['username'];?></span>
                                <?php if($user_info['username_status']==0){?>
                                <span id="changeUsername">修改用户名</span>
                                <?php }?>
                                <input type="text" id="uname"  maxlength="12">
								<div class="changeuser-btns">
                                    <button class="confirm green-btn" type="button" id="usernameSubBtn">确定</button>
                                    <button class="cancel cancel-btn" type="button" id="usernameCanBtn">取消</button>
                                </div>
                                <span class="ex-tips username-tips" id="usernameTips">用户名作为在网站内的昵称显示，只可修改一次</span>
                            </div>
                        </li>

                        <li>
                            <label for="truename">真实姓名</label>
                            <div class="inputbox">
                                <input type="text" id="truename" name="realname" value="<?php echo $user_info['realname'];?>" maxlength="10">
                                <span class="ex-tips">仅用户自己可见</span>
                            </div>
                        </li>
                        <li>
                            <label for="usex">性别</label>
                            <div class="inputbox">
                                <span class="select-box-sex"><select id="usex" name="sex">
                                    <option value="0" <?php echo $user_info['sex']==0?'selected':'';?> >保密</option> gender
                                    <option value="1" <?php echo $user_info['sex']==1?'selected':'';?> >男</option>
                                    <option value="2" <?php echo $user_info['sex']==2?'selected':'';?> >女</option>
                                </select></span>
                            </div>
                        </li>
                        <li>
                            <label for="calendar">生日</label>
                            <div class="inputbox">
                                <input class="laydate-icon" type="text" id="d1" type="text" name="birthday" onFocus="WdatePicker({isShowClear:false,readOnly:true})" value="<?php echo date('Y-m-d',$user_info['birthday']);?>" style="background: url('/public/static/admin/lib/My97DatePicker/4.8/skin/datePicker.gif') no-repeat 107px 50%;">
                            </div>
                        </li>
                        <li>
                            <label for="calendar">QQ</label>
                            <div class="inputbox">
                                <input class="laydate-icon" type="text" name="qq" value="<?php echo $user_info['qq'];?>">
                            </div>
                        </li>
                        <!--<li>-->
                            <!--<label>现居住地</label>-->
                            <!--<div class="inputbox">-->
                                <!--<span class="select-box-home"><select class="select-home" name="provice_id" id="proviceSel" data-value="0">-->
                                    <!--<option value="0">省</option>-->
                                <!--</select></span>-->
                                <!--<span class="select-box-home"><select class="select-home" name="city_id" id="citySel" data-value="0">-->
                                    <!--<option value="0">市</option>-->
                                <!--</select></span>-->
                            <!--</div>-->
                        <!--</li>-->
                        <li>
                            <label for="">个人简介</label>
                            <div class="inputbox">
                                <textarea placeholder="一句话介绍一下自己吧，让别人更了解你" maxlength="200" name="introduce"><?php echo $user_info['introduce'];?></textarea>
                                <p class="extra-tips"> <span id="tipsNum">200</span> 字以内</p>
                            </div>
                        </li>
                    </ul>
                    <button type="button" class="green-btn" id="submit-btn">保存</button>
                </form>
            </section>
            <section class="zhiye">
                <h3>职业信息</h3>
                <ul>
                    <li>
                        <label for="">职业状态</label>
                        <div class="status-pop">
                            <a id="zyStatus" class="zhiye-popup">添加职业状态</a>
                        </div>
                    </li>
                    <li>
                        <label for="">职业方向</label>
                        <div class="dir-pop">
                            <a id="zyDirection" class="zhiye-popup">添加感兴趣的职业方向</a>
                        </div>
                    </li>
                </ul>
            </section>
            <section class="domain">
                <h3>个性域名</h3>
                <div class="domain-area">
                    <label for="">我的个性域名</label>
					<a class="domain-area-setup">设置个性域名</a><span class="domain-area-url">http://my.jikexueyuan.com/<em id="sucDomain"></em></span>                    
                    <!-- <span class="domain-area-url">my.jikexueyuan.com/<em id="sucDomain"></em></span> -->
                    <div class="domain-area-input" style="display: none">
                        <span class="pre">my.jikexueyuan.com/</span>
                        <input type="text" id="domainChange" maxlength="25" value="">
                        <span class="tip">支持小写英文字母/下划线/数字，25个字符以内，设定后不可修改</span>
                        <div class="btns">
                            <button class="confirm green-btn" id="domainBtn">确定</button>
                            <button class="cancel cancel-btn">取消</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
		<!-- 修改头像 -->
        <div class="popup" style="display: none;" id="setPhotoPop">
            <header>
                <h2>修改头像</h2>
                <i class="close popclose"></i>
            </header>
            <div class="wrap">
                <div class="bd">
                    <p id="swfContainer">
                        本组件需要安装Flash Player后才可使用，请从<a href="http://www.adobe.com/go/getflashplayer">这里</a>下载安装。
                    </p>
                    <!-- <button type="button" id="upload" style="display:none;margin-top:8px;">上传</button> -->
                </div>
            </div>
        </div>
        <!-- /setPhotoPop -->
        <div class="popup" id="statusPop" style="display: none;">
            <header>
                <h2>添加职业状态</h2>
                <i class="close popclose"></i>
            </header>
            <div class="wrap">
                <h3>请选择您当前的职业状态：</h3>
                <ul class="status-ul cf" id="statusBtns">
                    <li data-value='1'>学生</li>
                    <li data-value='2'>在职</li>
                    <li data-value='3'>待业</li>
                </ul>
                <div class="status-selects" style="display: none;">
                    <span><select id="select-one"></select></span>
                    <span><select id="select-two"></select></span>
                </div>
                <div class="status-btns">
                    <button class="confirm gray-btn" id="zhiyeSub">确定</button>
                    <button class="cancel cancel-btn popclose">取消</button>
                </div>
            </div>
        </div>
        <div class="popup" id="directionPopone" style="display: none;">
            <header>
                <h2>添加感兴趣的职业方向（1/2）</h2>
                <i class="close popclose"></i>
            </header>
            <div class="wrap part1">
                <h3>请选择你感兴趣的职业方向（最多可选择3个）</h3>
                <ul id="directionUl" class="cf">
                </ul>
                <div class="direction-btns cf">
                    <button class="confirm gray-btn" id="dirSubbtn1">确定</button>
                    <button class="cancel cancel-btn popclose">取消</button>
                </div>
            </div>
        </div>
        <div class="popup" id="directionPoptwo" style="display: none;">
            <header>
                <h2>添加感兴趣的职业方向（2/2）</h2>
                <i class="close popclose"></i>
            </header>
            <div class="wrap part2">
                <h3>您在这些方向上当前的水平如何？</h3>
                <ul id="dirlevelUl">
                </ul>
                <div class="direction-btns cf">
                    <button class="confirm gray-btn" id="dirSubbtn2">确定</button>
                    <button class="cancel cancel-btn popclose">取消</button>
                </div>
            </div>
        </div>
    </div>
<style>

</style>
<script>
    $('#submit-btn').on('click',function(){
        //var data = $('#userinfoForm').serialize();
        var realname = $("input[name='realname']").val();
        var sex      = $('select[name="sex"]').val();
        var birthday = $('input[name="birthday"]').val();
        var qq       = $('input[name="qq"]').val();
        var introduce= $.trim($('textarea[name="introduce"]').val());
        if(introduce.length > 200){
            layer.msg('个人简介字数超过了最大长度哦'); return false;
        }

        //console.log(introduce.length);return false;
        $.ajax({
            url: "/start/user/index",
            type: 'POST',
            dataType: 'JSON',
            data: {'realname':realname,'sex':sex,'birthday':birthday,'qq':qq,'introduce':introduce},
            success:function (data) {
                if(data.error==1){
                    layer.msg(data.msg,{'icon':6,'time':1500,offset:['120px']});
                }else if(data.success==1){
                    $('#submit-btn').html('保存中..').css({'background-color':'#88DEA0','border':'1px solid #88DEA0','cursor':'text'}).attr('disabled',true);
                    setTimeout(function(){
                        layer.msg(data.msg,{'icon':1,'time':1000,offset:['120px']});
                        $('.username').text(username).show();
                        $('#uname').css({'display':'none'});
                        $('.changeuser-btns').css({'display':'none'});
                        $('#usernameTips').css({'display':'none'});
                    },800);
                    setTimeout(function(){
                        location.reload();
                    },1000);
                }
            },
            error:function(){
                layer.msg('网络繁忙 请稍后重试',{'icon':2,'time':1000});
            }
        });
    });

</script>
</body>
<script type="text/javascript" src="/public/static/start/js/form.js"></script>
<script type="text/javascript" src="/public/static/start/js/user/user.js"></script>
</html>
