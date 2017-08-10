<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\index\index.html";i:1489481760;s:67:"D:\phpStudy\WWW\Thinkphp_5.0/application/start\view\\index\nav.html";i:1487855340;}*/ ?>
﻿<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta property="qc:admins" content="1721742224651636" />
    <meta property="wb:webmaster" content="69e23ac12c635da9" />
    <meta name="baidu-site-verification" content="sSrE9nFHHr" />
    <meta baidu-gxt-verify-token="0cedd8469f067b548e2e2a19a196c16e">
    <meta name="360-site-verification" content="5dcd724ad92b8bba9adf192b493f6586" />
    <!--新浪id验证-->
    <meta property="wb:webmaster" content="ca69d467a5d84fc9" />
    <title>七里香-专业技术文章交互设计平台</title>

    <meta name="Keywords" content=""/>
    <meta name="Description" content=""/>

    <!-- CSS -->
    <link rel="stylesheet" href="/public/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css"/>
    <link rel="stylesheet" href="/public/static/start/css/uicn.v1.css">
    <link rel="stylesheet" href="/public/static/start/css/header.v1.css">
    <link rel="stylesheet" href="/public/static/start/css/footer.v1.css">
    <link rel="stylesheet" href="/public/static/start/css/nivo-slider.css">
    <link rel="stylesheet" href="/public/static/start/css/modal.css">
    <link rel="stylesheet" href="/public/static/start/css/post.css">

    <link rel="stylesheet" href="/public/static/start/css/simditor.css">
    <link rel="stylesheet" href="/public/static/start/css/ui-theme.css">

    <link rel="stylesheet" href="/public/static/start/css/contentover.css">
    <link rel="stylesheet" href="/public/static/start/css/page.css">
    <link rel="stylesheet" href="/public/static/start/css/min_page.css">

    <link rel="stylesheet" href="/public/static/start/css/home.v1.css">
    <link rel="stylesheet" href="/public/static/start/css/exp.css" >
    <link rel="stylesheet" href="/public/static/start/css/new.css"/>
    <link rel="stylesheet" href="/public/static/start/css/zhuce.css">


    <!-- JS -->
    <script src="/public/static/public/js/jquery-1.11.3.min.js"></script>
    <script src="/public/static/start/js/msgtip.js"></script>
    <script src="/public/static/start/js/header.v1.js"></script>
    <script src="/public/static/start/js/home.v1.js"></script>
    <script src="/public/static/start/js/works.js"></script>

    <script src="/public/static/start/js/footer.v1.js"></script>
    <script src="/public/static/start/js/post.js"></script>

    <script src="/public/static/start/js/lazyload.js"></script>
    <script src="/public/static/start/js/cnzz.js"></script>
    <!--[if lt IE 9]>
    <script src="/public/static/start/js/html5.js"></script>
    <![endif]-->
    <!--[if (gte IE 6)&(lte IE 8)]>
    <script src="/public/static/start/js/selectivizr.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/public/static/public/layer_pc/layer.js"></script>


</head>

<body>
<div id="ajax-hook"></div>

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

<!-- 	bg-white -->

<script>
    //动态默认显示
    var nav_loc = '';
    //var navuid='';
    //以下兼容个人中心 立即激活
    $(".jsemailverify").parent('div').parent('li').css("background", "#fff7e7").find('a').addClass('f14').css('display', 'block');
    $('#nav-index').addClass('on');
</script>

<!-- 轮播统计 -->

<script type="text/javascript">
    $(function () {
        var Li = $("#slider>a");
        Li.click(function () {
            var index = Li.index(this) + 1;
            _czc.push(["_trackEvent", "轮播统计", "点击", index]);
        });
    })
</script>
<!--  -->
<div class="slider-wrapper mtw">
    <div id="slider" class="nivoSlider">
        <a href="" class="adv_img" rel="619" target="_blank" title="2016年度作品集活动">
            <img src="/public/static/start/img/1483110963_317.png" alt="2016年度作品集活动">
        </a>
        <a href="" class="adv_img" rel="627" target="_blank" title="AE制作液态音乐播放效果">
            <img src="/public/static/start/img/1484212673_562.jpeg" alt="AE制作液态音乐播放效果">
        </a>
        <a href="" class="adv_img" rel="625" target="_blank" title="喜欢无人机吗，来鼠绘一个吧！">
            <img src="/public/static/start/img/1483928656_234.jpeg" alt="喜欢无人机吗，来鼠绘一个吧！">
        </a>
        <a href="" class="adv_img" rel="624" target="_blank" title="熊猫创想——实战型UED学院">
            <img src="/public/static/start/img/1483693173_399.jpeg" alt="熊猫创想——实战型UED学院">
        </a>
        <a href="" class="adv_img" rel="505" target="_blank" title="设计师认证">
            <img src="/public/static/start/img/1467958508_477.jpeg" alt="设计师认证">
        </a>
    </div>
</div>
<!--  -->

<!--  -->
<div class="wpn" id="project">
    <!--  -->
    <div class="cl pos">
        <ul class="h-screen">
            <li class="on"><a href="javascript:;" title="首页推荐">首页推荐</a></li>
            <li><a href="#" title="佳作分享">佳作分享</a></li>
            <li><a href="#" title="最新作品">最新作品</a></li>
        </ul>
        <!--  -->
        <ul class="h-soup cl">
            <li><i class="icon-star1" title="更新"></i>
                <a class="txt" href="/system.html" target="_blank"> 更新：导航栏更新~一起来发现！ </a>
            </li>
            <li class="open">
                <i class="icon-heart-round" title="鸡汤"></i>
                <a class="txt" href="/soup.html" target="_blank"> 鸡汤：绽放时厚积薄发，等待时心如止水~ </a>
            </li>
            <li>
                <i class="icon-warn" title="公告"></i>
                <a class="txt" href="/site.html" target="_blank"> 公告：关于作品／文章推荐，UI中国特此声明 </a>
            </li>
        </ul>
    </div>
    <!--  -->
    <!--  -->
    <ul class="post post-works mtw cl">

        <li>
            <!--  -->
            <div class="shade"></div>
            <!--  -->
            <div class="cover pos">
                <a href="/detail/203511.html" target="_blank" title="回忆集：铁甲小宝">
                    <img width="280" height="210" src="/public/static/start/img/1px.png" data-original="/public/static/start/img/zhuce_background.png" class="imgloadinglater" alt="回忆集：铁甲小宝" rel="nofollow">
                </a>
            </div>
            <div class="info">
                <h4 class="title ellipsis download">
                    回忆集：铁甲小宝                                </h4>
                <div class="msg mtn cl">
                    <span class="classify">原创</span>
                    <span><i class="icon-eye" title="浏览数"></i><em>1425</em></span>
                    <span><i class="icon-comment" title="评论数"></i><em>27</em></span>
                    <span><i class="icon-leaf" title="点赞数"></i><em>126</em></span>
                </div>
                <p class="user cl">
                    <a href="http://i.ui.cn/ucenter/957427.html" target="_blank"><img src="/public/static/start/img/957427.jpg" title="Lachie一li"> <strong class="name">
                        <em>Lachie一li</em>
                        <i class="cert icon-certified2" title="UI中国认证设计师"></i>                                                                        </strong></a>
                </p>
            </div>
            <!--  分隔线  -->
            <div class="line"></div>
        </li>
        <li>
            <!--  -->
            <div class="shade"></div>
            <!--  -->
            <div class="cover pos">
                <a href="/detail/203506.html" target="_blank" title="【我的2016】一份非正式的工作总结">
                    <img width="280" height="210" src="/public/static/start/img/1px.png" data-original="/public/static/start/img/zhuce_background.png" class="imgloadinglater" alt="【我的2016】一份非正式的工作总结" rel="nofollow">
                </a>
            </div>
            <div class="info">
                <h4 class="title ellipsis download">
                    【我的2016】一份非正式的工作总结                                </h4>
                <div class="msg mtn cl">
                    <span class="classify">原创</span>
                    <span><i class="icon-eye" title="浏览数"></i><em>3112</em></span>
                    <span><i class="icon-comment" title="评论数"></i><em>43</em></span>
                    <span><i class="icon-leaf" title="点赞数"></i><em>308</em></span>
                </div>
                <p class="user cl">
                    <a href="http://i.ui.cn/ucenter/266740.html" target="_blank"><img src="/public/static/start/img/266740.png" title="蛋黄也也酥"> <strong class="name">
                        <em>蛋黄也也酥</em>
                    </strong></a>
                </p>
            </div>
            <!--  分隔线  -->
            <div class="line"></div>
        </li>
        <li>
            <!--  -->
            <div class="shade"></div>
            <!--  -->
            <div class="cover pos">
                <a href="/detail/198703.html" target="_blank" title="PANTS/鸡飞狗跳">
                    <img width="280" height="210" src="/public/static/start/img/1px.png" data-original="/public/static/start/img/zhuce_background.png" class="imgloadinglater" alt="PANTS/鸡飞狗跳" rel="nofollow">
                </a>
            </div>
            <div class="info">
                <h4 class="title ellipsis download">
                    PANTS/鸡飞狗跳                                </h4>
                <div class="msg mtn cl">
                    <span class="classify">原创</span>
                    <span><i class="icon-eye" title="浏览数"></i><em>1823</em></span>
                    <span><i class="icon-comment" title="评论数"></i><em>19</em></span>
                    <span><i class="icon-leaf" title="点赞数"></i><em>107</em></span>
                </div>
                <p class="user cl">
                    <a href="http://i.ui.cn/ucenter/298758.html" target="_blank"><img src="/public/static/start/img/298758.png" title="大裤衩"> <strong class="name">
                        <em>大裤衩</em>
                    </strong></a>
                </p>
            </div>
            <!--  分隔线  -->
            <div class="line"></div>
        </li>
        <li>
            <!--  -->
            <div class="shade"></div>
            <!--  -->
            <div class="cover pos">
                <a href="/detail/196478.html" target="_blank" title="【渐变插画】不如来珠海转转">
                    <img width="280" height="210" src="/public/static/start/img/1px.png" data-original="/public/static/start/img/zhuce_background.png" class="imgloadinglater" alt="【渐变插画】不如来珠海转转" rel="nofollow">
                </a>
            </div>
            <div class="info">
                <h4 class="title ellipsis download">
                    【渐变插画】不如来珠海转转                                </h4>
                <a href="#" name="p-down1" class="i-rar" title="登陆后可下载">下载</a>
                <div class="msg mtn cl">
                    <span class="classify">原创</span>
                    <span><i class="icon-eye" title="浏览数"></i><em>4309</em></span>
                    <span><i class="icon-comment" title="评论数"></i><em>41</em></span>
                    <span><i class="icon-leaf" title="点赞数"></i><em>308</em></span>
                </div>
                <p class="user cl">
                    <a href="http://i.ui.cn/ucenter/134010.html" target="_blank"><img src="/public/static/start/img/134010.jpg" title="肉桑大魔王Roshan"> <strong class="name">
                        <em>肉桑大魔王Roshan</em>
                        <i class="cert icon-certified2" title="UI中国认证设计师"></i>                                                                        </strong></a>
                </p>
            </div>
            <!--  分隔线  -->
            <div class="line"></div>
        </li>
         </ul>
    <div class="h_page mtn mbw">
        <ul class='cl'>
            <li><a class='on' href='javascript:;'>1</a></li>
            <li><a href='?p=2#project'>2</a></li>
            <li><a href='?p=3#project'>3</a></li>
            <li><a href='/list.html?r=main&p=2' target='_blank'>...</a></li>
        </ul>
    </div>
    <!--  -->
</div>
<!--  -->

<!--  -->
<div class="bg-white" id="article">
    <div class="wpn cl">
        <div class="w820 z">
            <h1 class="h-tit mtv h-article-btn pos">
                <a class="on" href="javascript:;" title="文章">推荐文章</a>

                <a href="/?art=article#article" title="最新文章">最新文章</a>            </h1>

            <div class="h-article-box">
                <ul class="h-article-list">
                    <li class="cl">
                        <a href="/detail/201560.html" title="新年目标：三条路径成为经验丰富的设计师" target="_blank">
                            <div class="shade"></div>
                            <div class="showd">
                                        <span class="cover" >
                                            <img width="160" height="120" src="/public/static/start/img/956782.png" alt="新年目标：三条路径成为经验丰富的设计师" rel="nofollow">
                                        </span>
                                <div class="h-article-info">
                                    <h1 class="cl">
                                        <span class="tag bg-blue" target="_blank" href="/exp.html?scatid=15">经验观点</span>
                                        <span class="ellipsis" href="/detail/201560.html" title="新年目标：三条路径成为经验丰富的设计师" target="_blank">新年目标：三条路径成为经验丰富的设计师</span>
                                    </h1>
                                    <p>这不仅仅是一篇经验，而是前辈用日积月累得工作心得，用笔记用脑想，最重要得是要走心。还有我是占位符不是简介。</p>
                                    <div class="mtm cl">
                                                <span class="avatar z" href="http://i.ui.cn/ucenter/362671.html" title="厉嗣傲" target="_blank">
                                                    <img width="20" height="20" src="/public/static/start/img/362671_1.jpg" alt="厉嗣傲" rel="nofollow">
                                                    <strong class="name">厉嗣傲                                                    <i class="icon-certified2" title="UI中国认证设计师"></i>                                                                                                    </strong>

                                                </span>
                                        <div class="msg z cl">
                                            <span><i class="icon-eye"></i><em>2885</em></span>
                                            <span><i class="icon-comment"></i><em>3</em></span>
                                            <span><i class="icon-leaf"></i><em>41</em></span>
                                        </div>
                                    </div>
                                    <div class="data"><i class="icon-time"></i>1-5 10:48</div>
                                </div>
                            </div>
                        </a>
                    </li><li class="cl">
                    <a href="/detail/201257.html" title="一波全新的Google设计作品" target="_blank">
                        <div class="shade"></div>
                        <div class="showd">
                                        <span class="cover" >
                                            <img width="160" height="120" src="/public/static/start/img/955217.png" alt="一波全新的Google设计作品" rel="nofollow">
                                        </span>
                            <div class="h-article-info">
                                <h1 class="cl">
                                    <span class="tag bg-blue" target="_blank" href="/exp.html?scatid=15">经验观点</span>
                                    <span class="ellipsis" href="/detail/201257.html" title="一波全新的Google设计作品" target="_blank">一波全新的Google设计作品</span>
                                </h1>
                                <p>这不仅仅是一篇经验，而是前辈用日积月累得工作心得，用笔记用脑想，最重要得是要走心。还有我是占位符不是简介。</p>
                                <div class="mtm cl">
                                                <span class="avatar z" href="http://i.ui.cn/ucenter/908178.html" title="Iris_Uu" target="_blank">
                                                    <img width="20" height="20" src="/public/static/start/img/908178.png" alt="Iris_Uu" rel="nofollow">
                                                    <strong class="name">Iris_Uu                                                                                                                                                        </strong>

                                                </span>
                                    <div class="msg z cl">
                                        <span><i class="icon-eye"></i><em>4818</em></span>
                                        <span><i class="icon-comment"></i><em>15</em></span>
                                        <span><i class="icon-leaf"></i><em>109</em></span>
                                    </div>
                                </div>
                                <div class="data"><i class="icon-time"></i>1-4 09:11</div>
                            </div>
                        </div>
                    </a>
                </li><li class="cl">
                    <a href="/detail/200525.html" title="视觉设计色彩篇 | 今天我们聊一下屏幕里「色色」的事情" target="_blank">
                        <div class="shade"></div>
                        <div class="showd">
                                        <span class="cover" >
                                            <img width="160" height="120" src="/public/static/start/img/954418.png" alt="视觉设计色彩篇 | 今天我们聊一下屏幕里「色色」的事情" rel="nofollow">
                                        </span>
                            <div class="h-article-info">
                                <h1 class="cl">
                                    <span class="tag bg-blue" target="_blank" href="/exp.html?scatid=15">经验观点</span>
                                    <span class="ellipsis" href="/detail/200525.html" title="视觉设计色彩篇 | 今天我们聊一下屏幕里「色色」的事情" target="_blank">视觉设计色彩篇 | 今天我们聊一下屏幕里「色色」的事情</span>
                                </h1>
                                <p>这不仅仅是一篇经验，而是前辈用日积月累得工作心得，用笔记用脑想，最重要得是要走心。还有我是占位符不是简介。</p>
                                <div class="mtm cl">
                                                <span class="avatar z" href="http://i.ui.cn/ucenter/946285.html" title="一起开工" target="_blank">
                                                    <img width="20" height="20" src="/public/static/start/img/946285.png" alt="一起开工" rel="nofollow">
                                                    <strong class="name">一起开工                                                                                                                                                        </strong>

                                                </span>
                                    <div class="msg z cl">
                                        <span><i class="icon-eye"></i><em>2947</em></span>
                                        <span><i class="icon-comment"></i><em>2</em></span>
                                        <span><i class="icon-leaf"></i><em>55</em></span>
                                    </div>
                                </div>
                                <div class="data"><i class="icon-time"></i>1-3 16:04</div>
                            </div>
                        </div>
                    </a>
                </li><li class="cl">
                    <a href="/detail/201100.html" title="_小帅带你画UI－QQ邮箱启动页插画" target="_blank">
                        <div class="shade"></div>
                        <div class="showd">
                                        <span class="cover" >
                                            <img width="160" height="120" src="/public/static/start/img/954248.jpg" alt="_小帅带你画UI－QQ邮箱启动页插画" rel="nofollow">
                                        </span>
                            <div class="h-article-info">
                                <h1 class="cl">
                                    <span class="tag bg-blue" target="_blank" href="/exp.html?scatid=11">设计教程</span>
                                    <span class="ellipsis" href="/detail/201100.html" title="_小帅带你画UI－QQ邮箱启动页插画" target="_blank">_小帅带你画UI－QQ邮箱启动页插画</span>
                                </h1>
                                <p>这不仅仅是一篇经验，而是前辈用日积月累得工作心得，用笔记用脑想，最重要得是要走心。还有我是占位符不是简介。</p>
                                <div class="mtm cl">
                                                <span class="avatar z" href="http://i.ui.cn/ucenter/195887.html" title="_小帅" target="_blank">
                                                    <img width="20" height="20" src="/public/static/start/img/195887.jpg" alt="_小帅" rel="nofollow">
                                                    <strong class="name">_小帅                                                    <i class="icon-certified2" title="UI中国认证设计师"></i>                                                                                                    </strong>

                                                </span>
                                    <div class="msg z cl">
                                        <span><i class="icon-eye"></i><em>5964</em></span>
                                        <span><i class="icon-comment"></i><em>38</em></span>
                                        <span><i class="icon-leaf"></i><em>388</em></span>
                                    </div>
                                </div>
                                <div class="data"><i class="icon-time"></i>1-3 15:25</div>
                            </div>
                        </div>
                    </a>
                </li><li class="cl">
                    <a href="/detail/201325.html" title="服务设计的小九九 ②" target="_blank">
                        <div class="shade"></div>
                        <div class="showd">
                                        <span class="cover" >
                                            <img width="160" height="120" src="/public/static/start/img/955670.jpg" alt="服务设计的小九九 ②" rel="nofollow">
                                        </span>
                            <div class="h-article-info">
                                <h1 class="cl">
                                    <span class="tag bg-blue" target="_blank" href="/exp.html?scatid=15">经验观点</span>
                                    <span class="ellipsis" href="/detail/201325.html" title="服务设计的小九九 ②" target="_blank">服务设计的小九九 ②</span>
                                </h1>
                                <p>服务设计的五个原则</p>
                                <div class="mtm cl">
                                                <span class="avatar z" href="http://i.ui.cn/ucenter/157699.html" title="萨小摩" target="_blank">
                                                    <img width="20" height="20" src="/public/static/start/img/157699.jpeg" alt="萨小摩" rel="nofollow">
                                                    <strong class="name">萨小摩                                                                                                                                                        </strong>

                                                </span>
                                    <div class="msg z cl">
                                        <span><i class="icon-eye"></i><em>1202</em></span>
                                        <span><i class="icon-comment"></i><em>1</em></span>
                                        <span><i class="icon-leaf"></i><em>10</em></span>
                                    </div>
                                </div>
                                <div class="data"><i class="icon-time"></i>1-4 15:31</div>
                            </div>
                        </div>
                    </a>
                </li><li class="cl">
                    <a href="/detail/200459.html" title="跟着电台动起来" target="_blank">
                        <div class="shade"></div>
                        <div class="showd">
                                        <span class="cover" >
                                            <img width="160" height="120" src="/public/static/start/img/956090.png" alt="跟着电台动起来" rel="nofollow">
                                        </span>
                            <div class="h-article-info">
                                <h1 class="cl">
                                    <span class="tag bg-blue" target="_blank" href="/exp.html?scatid=15">经验观点</span>
                                    <span class="ellipsis" href="/detail/200459.html" title="跟着电台动起来" target="_blank">跟着电台动起来</span>
                                </h1>
                                <p>如何设计跑步的垂直场景</p>
                                <div class="mtm cl">
                                                <span class="avatar z" href="http://i.ui.cn/ucenter/85974.html" title="腾讯ISUX" target="_blank">
                                                    <img width="20" height="20" src="/public/static/start/img/85974.jpg" alt="腾讯ISUX" rel="nofollow">
                                                    <strong class="name">腾讯ISUX                                                                                                                                                        </strong>

                                                </span>
                                    <div class="msg z cl">
                                        <span><i class="icon-eye"></i><em>2497</em></span>
                                        <span><i class="icon-comment"></i><em>8</em></span>
                                        <span><i class="icon-leaf"></i><em>19</em></span>
                                    </div>
                                </div>
                                <div class="data"><i class="icon-time"></i>1-4 13:32</div>
                            </div>
                        </div>
                    </a>
                </li>                </ul>
                <div class="h_page mtw mbw">
                    <ul class='cl'><li><a class='on' href='javascript:;'>1</a></li><li><a href='?page=2#article'>2</a></li><li><a href='?page=3#article'>3</a></li><li><a href='/exp.html?tag=1&p=3' target='_blank'>...</a></li></ul>                </div>

            </div>

        </div>
        <div class="w280 y mtv">
            <h1 class="h-tit-aside">热招职位</h1>
            <ul class="h-aside-list">
                <li class="pos">
                    <a href="http://zhaopin.ui.cn/prev/pid/481.html" target="_blank">
                        <img class="cover" width="280" height="125" src="/public/static/start/img/1482144673_737.jpeg" alt="" rel="nofollow">
                        <div class="h-aside-show">
                            <p class="item ellipsis">[西安] 交互设计师 [5k-15k]</p><p class="item ellipsis">[西安] 视觉设计师 [5k-15k]</p><p class="item ellipsis">[西安] 设计实习生 [3k-5k]</p>                            </div>
                    </a>
                </li><li class="pos">
                <a href="http://zhaopin.ui.cn/prev/pid/373.html" target="_blank">
                    <img class="cover" width="280" height="125" src="/public/static/start/img/1482144569_472.jpeg" alt="" rel="nofollow">
                    <div class="h-aside-show">
                        <p class="item ellipsis">[北京市] 【急聘】APP设计师 [30k-60k]</p><p class="item ellipsis">[北京市] 资深交互设计师 [25k-40k]</p><p class="item ellipsis">[北京市] UI/视觉高级设计师 [20k-35k]</p><p class="item ellipsis">[北京市] 交互设计专家 [25k-35k]</p>                            </div>
                </a>
            </li><li class="pos">
                <a href="http://zhaopin.ui.cn/prev/pid/399.html" target="_blank">
                    <img class="cover" width="280" height="125" src="/public/static/start/img/1477368289_835.jpeg" alt="" rel="nofollow">
                    <div class="h-aside-show">
                        <p class="item ellipsis">[北京市] 3D 高级角色模型师 [20k-30k]</p><p class="item ellipsis">[北京市] 高级动作师 [20k-30k]</p><p class="item ellipsis">[北京市] 视觉特效师 [20k-30k]</p><p class="item ellipsis">[北京市] UI设计师 [20k-30k]</p>                            </div>
                </a>
            </li>            </ul>
            <a class="more" href="http://zhaopin.ui.cn/" title="招聘" target="_blank">更多</a>

            <h1 class="h-tit-aside mtw">热门课程</h1>
            <ul class="h-aside-list">
                <li class="pos">
                    <a href="http://peixun.ui.cn/detail/388.html" target="_blank">
                        <img class="cover" width="280" height="125" src="/public/static/start/img/1478063929_706.jpeg" alt="" rel="nofollow">
                    </a>
                </li><li class="pos">
                <a href="http://peixun.ui.cn/detail/364.html" target="_blank">
                    <img class="cover" width="280" height="125" src="/public/static/start/img/1473390683_245.jpeg" alt="" rel="nofollow">
                </a>
            </li><li class="pos">
                <a href="http://peixun.ui.cn/detail/347.html" target="_blank">
                    <img class="cover" width="280" height="125" src="/public/static/start/img/1468556269_167.jpeg" alt="" rel="nofollow">
                </a>
            </li>            </ul>
            <a class="more" href="http://peixun.ui.cn/" title="培训" target="_blank">更多</a>

        </div>
    </div>
</div>

<!--  -->

<!--  -->
<div class="wpn ptbw cl">
    <div class="h-novice h-novice-first">
        <img class="cover" width="334" height="134" src="/public/static/start/img/xinshou-680x280.png" alt="初级设计师必读专题" rel="nofollow">
        <ul class="list">
            <li>
                <a class="ellipsis" href="/detail/23826.html" title="【设计师配色宝典！教你从零开始学配色】" target="_blank">【设计师配色宝典！教你从零开始学配色】</a>                    </li><li>
            <a class="ellipsis" href="/detail/33056.html" title="合理利用对齐" target="_blank">合理利用对齐</a>                    </li><li>
            <a class="ellipsis" href="/detail/16467.html" title="《U1》05 - 三年入职大公司" target="_blank">《U1》05 - 三年入职大公司</a>                    </li><li>
            <a class="ellipsis" href="/detail/32936.html" title="设计师该如何快速成长？" target="_blank">设计师该如何快速成长？</a>                    </li><li>
            <a class="ellipsis" href="/detail/86725.html" title="iOS APP设计稿选择" target="_blank">iOS APP设计稿选择</a>                    </li><li>
            <a class="ellipsis" href="/detail/33272.html" title="配色剖析之绿色" target="_blank">配色剖析之绿色</a>                    </li><li>
            <a class="ellipsis" href="/detail/33174.html" title="什么是极简主义" target="_blank">什么是极简主义</a>                    </li><li>
            <a class="ellipsis" href="/detail/89348.html" title="UI设计师们都快要失业了吗" target="_blank">UI设计师们都快要失业了吗</a>                    </li><li>
            <a class="ellipsis" href="/detail/32937.html" title="设计师最常见的11个设计误区" target="_blank">设计师最常见的11个设计误区</a>                    </li><li>
            <a class="ellipsis" href="/detail/88195.html" title="10条建议打造更聪明的设计流程" target="_blank">10条建议打造更聪明的设计流程</a>                    </li><li>
            <a class="ellipsis" href="/detail/15748.html" title="如何寻找设计灵感？写给刚入行的设计师" target="_blank">如何寻找设计灵感？写给刚入行的设计师</a>                    </li><li>
            <a class="ellipsis" href="/detail/33176.html" title="我们为何要在设计中重视“留白”" target="_blank">我们为何要在设计中重视“留白”</a>                    </li><li>
            <a class="ellipsis" href="/detail/88166.html" title="初识产品设计" target="_blank">初识产品设计</a>                    </li>            </ul>
        <a class="more" href="http://topic.ui.cn/" target="_blank" title="专题">更多...</a>
    </div>

    <div class="h-novice">
        <div class="pos">
            <a class="cover" href="/detail/202291.html" target="_blank">
                <img width="280" height="210" src="/public/static/start/img/1px.png" data-original="/public/static/start/img/1468556269_167.jpeg" class="imgloadinglater"  alt="动效开发——100%还原运动曲线" rel="nofollow">
            </a>
            <i class="icon-hexagon-tag h-novice-tag"><span class="txt">观点</span></i>
            <div class="info">
                <h1><a href="/detail/202291.html" target="_blank">动效开发——100%还原运动曲线</a></h1>
                <p class="txt">相信大家在和开发人员一起还原动效的过程中都会遇到不少困难，本文就其中一个困难&mdash;&mdash;输出运动曲线，为大家介绍一个方法。</p>

            </div>
            <div class="author cl">
                <a href="http://i.ui.cn/ucenter/938748.html" class="avatar z" target="_blank"><img width="50" height="50" src="/public/static/start/img/1px.png" data-original="http://imgavater.ui.cn/avatar/8/4/7/8/938748.png?imageMogr2/auto-orient/crop/!242x242a135a120/thumbnail/100x100" class="imgloadinglater" alt="Tlaloc" rel="nofollow"></a>
                <div class="z">
                    <h5 class="name"><a href="http://i.ui.cn/ucenter/938748.html" target="_blank">Tlaloc                                                                        </a></h5>
                    <div class="msg cl">
                        <span><i class="icon-eye"></i><em>3306</em></span>
                        <span><i class="icon-comment"></i><em>20</em></span>
                        <span><i class="icon-leaf"></i><em>89</em></span>
                    </div>
                </div>
            </div>
        </div>
        <a class="cover" href="http://topic.ui.cn/detail?tid=39" target="_blank">
            <img class="cover imgloadinglater" width="280" height="210" src="/public/static/start/img/1px.png" data-original="/Public//public/static/start/img/shouye/linian-560x420.png" alt="UI设计规范" rel="nofollow">
        </a>
    </div>

    <div class="h-novice">
        <a class="cover pos" href="http://study.ui.cn/list.html" target="_blank">
            <img width="280" height="210" src="/public/static/start/img/02c322ad75d42df39b05173a13ddb972.jpeg" alt="第一百一十九期：AE制作液态音乐播放效果" rel="nofollow">
            <i class="icon-hexagon-tag h-novice-tag purple"><span class="txt">第119期</span></i>
        </a>
        <div class="pos">
            <a class="cover" href="/detail/202404.html" target="_blank"><img width="280" height="210" src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/file/8/6/2/961268.jpg" class="imgloadinglater" alt="混合型界面:对话式UI的未来" rel="nofollow"></a>
            <i class="icon-hexagon-tag h-novice-tag"><span class="txt">教程</span></i>
            <div class="info">
                <h1><a href="/detail/202404.html" target="_blank">混合型界面:对话式UI的未来</a></h1>
                <p class="txt">对话式UI近年来非常火热，但它的前景显然不仅仅限于图文聊天，加入各种富媒体功能与应用之后，它的能力将有巨大的想象空间。</p>

            </div>
            <div class="author cl">
                <a href="http://i.ui.cn/ucenter/93876.html" class="avatar z" target="_blank"><img width="50" height="50" src="/public/static/start/img/93876.100x100.png" alt="可乐橙" rel="nofollow"></a>
                <div class="z">
                    <h5 class="name"><a href="http://i.ui.cn/ucenter/93876.html" target="_blank">可乐橙                            <i class="cert mar2 icon-certified2" title="UI中国认证设计师"></i>                                                        </a></h5>
                    <div class="msg cl">
                        <span><i class="icon-eye"></i><em>4333</em></span>
                        <span><i class="icon-comment"></i><em>4</em></span>
                        <span><i class="icon-leaf"></i><em>33</em></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-novice">
        <div class="pos">
            <a class="cover" href="/detail/201560.html" target="_blank">
                <img width="280" height="210" src="/public/static/start/img/1px.png" data-original="/public/static/start/img/956782.png" class="imgloadinglater" alt="新年目标：三条路径成为经验丰富的设计师" rel="nofollow">
            </a>
            <i class="icon-hexagon-tag h-novice-tag"><span class="txt">观点</span></i>
            <div class="info">
                <h1><a href="/detail/201560.html" target="_blank">新年目标：三条路径成为经验丰富的设计师</a></h1>
                <p class="txt">这不仅仅是一篇经验，而是前辈用日积月累得工作心得，用笔记用脑想，最重要得是要走心。还有我是占位符不是简介。</p>

            </div>
            <div class="author cl">
                <a href="http://i.ui.cn/ucenter/362671.html" class="avatar z" target="_blank"><img width="50" height="50" src="/public/static/start/img/362671_1.jpg" alt="厉嗣傲" rel="nofollow"></a>
                <div class="z">
                    <h5 class="name"><a href="http://i.ui.cn/ucenter/362671.html" target="_blank">厉嗣傲                            <i class="cert mar2 icon-certified2" title="UI中国认证设计师"></i>                                                        </a></h5>
                    <div class="msg cl">
                        <span><i class="icon-eye"></i><em>2885</em></span>
                        <span><i class="icon-comment"></i><em>3</em></span>
                        <span><i class="icon-leaf"></i><em>41</em></span>
                    </div>
                </div>
            </div>
        </div>
        <a class="cover" href="http://topic.ui.cn/detail?tid=45" target="_blank">
            <img class="cover" width="280" height="210" src="/public/static/start/img/source-560x420.png" alt="源文件专题" rel="nofollow">
        </a>
    </div>
</div>
<!--  -->

<!--  -->
<div class="wpn ">
    <!--  -->
    <h1 class="h-tit ptv h-member-btn pos">
        <a class="on" href="javascript:;" title="推荐设计师">推荐设计师</a>
        <a href="javascript:;" title="认证设计师">认证设计师</a>
        <a href="javascript:;" title="活跃设计师">活跃设计师</a>
    </h1>
    <div class="h-member-box">
        <ul class="h-member cl">
            <li class="cl">
                <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/160727.html" target="_blank">
                    <img rel="nofollow" src="/public/static/start/img/160727.jpeg" alt="飞屋睿">
                </a>
                <div class="cont">
                    <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/160727.html">飞屋睿</a></h5>
                    <p class="text">http://www.ifeiwu.com</p>
                </div>
            </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/84039.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/84039.png" alt="hixulei">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/84039.html">hixulei</a></h5>
                <p class="text">微信公号: jingdesign91,欢迎关注.</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/211406.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/211406.png" alt="设计夹">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/211406.html">设计夹</a></h5>
                <p class="text">好内容尽在公众号：sezign</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/266148.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/266148.jpg" alt="Zustin">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/266148.html">Zustin</a></h5>
                <p class="text">炸絲_https://dribbble.com/Zustin</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/134010.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/134010_1.jpg" alt="肉桑大魔王Roshan">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/134010.html">肉桑大魔王Roshan</a></h5>
                <p class="text">MDD.design | dribbble.com/TGR</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/105781.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/105781.png" alt="Donkeyang">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/105781.html">Donkeyang</a></h5>
                <p class="text">https://soupman.carrd.co/</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/205412.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/205412.jpeg" alt="D_阿牛">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/205412.html">D_阿牛</a></h5>
                <p class="text">小米视觉设计师</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="" href="http://i.ui.cn/ucenter/58164.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/58164.jpeg" alt="JackCRing">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/58164.html">JackCRing</a></h5>
                <p class="text"></p>
            </div>
        </li>            </ul>
        <ul class="h-member cl">
            <li class="cl">
                <a class="avatar-sm" title="jeefuidesign" href="http://i.ui.cn/ucenter/889007.html" target="_blank">
                    <img rel="nofollow" src="/public/static/start/img/889007.jpg" alt="jeefuidesign">
                </a>
                <div class="cont">
                    <h5 class="user">
                        <a target="_blank" href="http://i.ui.cn/ucenter/889007.html">jeefuidesign                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                        </a>
                    </h5>
                    <p class="text">创意突显新意，细节决定成败。</p>
                </div>
            </li><li class="cl">
            <a class="avatar-sm" title="X努力努力再努力X" href="http://i.ui.cn/ucenter/889046.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/889046.jpg" alt="X努力努力再努力X">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/889046.html">X努力努力再努力X                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text">爱生活爱设计的社会栋梁！！！</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="倾城一叶" href="http://i.ui.cn/ucenter/889886.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/889886.jpg" alt="倾城一叶">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/889886.html">倾城一叶                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text"></p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="DW小宇" href="http://i.ui.cn/ucenter/889988.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/889988.jpg" alt="DW小宇">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/889988.html">DW小宇                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text">UI设计师</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="In_别样时光" href="http://i.ui.cn/ucenter/890771.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/890771.jpg" alt="In_别样时光">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/890771.html">In_别样时光                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text">乐于分享</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="绿姑娘jq" href="http://i.ui.cn/ucenter/891635.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/891635.jpg" alt="绿姑娘jq">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/891635.html">绿姑娘jq                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text">读万卷书，行万里路。</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="ljgty" href="http://i.ui.cn/ucenter/893541.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/893541.jpg" alt="ljgty">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/893541.html">ljgty                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text">1是大大大的sadasdsad</p>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="要念紧箍咒喽" href="http://i.ui.cn/ucenter/894466.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/894466.jpg" alt="要念紧箍咒喽">
            </a>
            <div class="cont">
                <h5 class="user">
                    <a target="_blank" href="http://i.ui.cn/ucenter/894466.html">要念紧箍咒喽                                    <i class="icon-certified2" title="UI中国认证设计师"></i>
                    </a>
                </h5>
                <p class="text">我的路在我脚下，你想走吗？</p>
            </div>
        </li>            </ul>
        <ul class="h-member cl">
            <li class="cl">
                <a class="avatar-sm" title="张脖鹿" href="http://i.ui.cn/ucenter/213066.html" target="_blank">
                    <img rel="nofollow" src="/public/static/start/img/213066.jpg" alt="张脖鹿">
                </a>
                <div class="cont">
                    <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/213066.html">张脖鹿                                                                                    </a>
                    </h5>
                    <p class="text">有三年电子商务平台网页设计及APP设计经验</p>
                    <div class="date">最近一周:发布20,被赞0,评论0</div>
                </div>
            </li><li class="cl">
            <a class="avatar-sm" title="Eithne" href="http://i.ui.cn/ucenter/282592.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/282592.jpg" alt="Eithne">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/282592.html">Eithne                                                                                    </a>
                </h5>
                <p class="text"></p>
                <div class="date">最近一周:发布13,被赞1,评论0</div>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="wike" href="http://i.ui.cn/ucenter/961732.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/961732.jpg" alt="wike">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/961732.html">wike                                                                                    </a>
                </h5>
                <p class="text"></p>
                <div class="date">最近一周:发布9,被赞0,评论0</div>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="上海海淘科技" href="http://i.ui.cn/ucenter/958100.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/958100.jpg" alt="上海海淘科技">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/958100.html">上海海淘科技                                                                                    </a>
                </h5>
                <p class="text">上海网站建设，上海网站制作，国内十年资深技术10年，120%满意</p>
                <div class="date">最近一周:发布8,被赞11,评论0</div>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="天才与狗丶" href="http://i.ui.cn/ucenter/927224.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/big.png" alt="天才与狗丶">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/927224.html">天才与狗丶                                                                                    </a>
                </h5>
                <p class="text"></p>
                <div class="date">最近一周:发布8,被赞0,评论0</div>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="bjxywhl" href="http://i.ui.cn/ucenter/208424.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/208424.jpg" alt="bjxywhl">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/208424.html">bjxywhl                                                                                    </a>
                </h5>
                <p class="text">我们不是为了设计，而是为了和这个世界相遇。</p>
                <div class="date">最近一周:发布7,被赞0,评论1</div>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="OsakaChihiro" href="http://i.ui.cn/ucenter/961636.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/961636.jpg" alt="OsakaChihiro">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/961636.html">OsakaChihiro                                                                                    </a>
                </h5>
                <p class="text">不想当咸鱼</p>
                <div class="date">最近一周:发布7,被赞0,评论0</div>
            </div>
        </li><li class="cl">
            <a class="avatar-sm" title="点歌频道" href="http://i.ui.cn/ucenter/961170.html" target="_blank">
                <img rel="nofollow" src="/public/static/start/img/961170.jpg" alt="点歌频道">
            </a>
            <div class="cont">
                <h5 class="user"><a target="_blank" href="http://i.ui.cn/ucenter/961170.html">点歌频道                                                                                    </a>
                </h5>
                <p class="text">坚持带来幸运，努力改变结果。</p>
                <div class="date">最近一周:发布7,被赞1,评论0</div>
            </div>
        </li>            </ul>
    </div>
    <div class="line"></div>
    <!--  -->
    <!--  -->
    <!--
    <h1 class="h-tit2">合作伙伴</h1>
    <div class="h-partners cl">
        <ul class="logo cl">
            <li>
                <a href="http://www.mi.com/" target="_blank" title="小米">
                <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201511/1447656039_430.png" class="imgloadinglater" alt="小米" rel="nofollow"></a>
            </li>
            <li>
                <a href="http://www.vchello.com/" target="_blank" title="Vchello">
                <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201511/1447654816_196.png" class="imgloadinglater" alt="Vchello" rel="nofollow"></a></li>
            <li>
                <a href="http://www.adquan.com/" target="_blank" title="广告门">
                 <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201610/1476325986_293.png" class="imgloadinglater" alt="广告门" rel="nofollow"></a></li>
            <li>
                <a href="http://cdc.tencent.com/" target="_blank" title="腾讯CDC">
                <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201610/1477367713_977.png" class="imgloadinglater" alt="腾讯CDC" rel="nofollow"></a></li>
            <li>
                <a href="http://mux.baidu.com" target="_blank" title="百度mux">
                 <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201605/1462355924_645.png" class="imgloadinglater" alt="百度mux" rel="nofollow"></a></li>
            <li>
                <a href="http://www.qiniu.com/" target="_blank" title="七牛云存储">
                <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201611/1478232729_344.png" class="imgloadinglater" alt="七牛云存储" rel="nofollow"></a></li>
            <li>
                <a href="https://www.ucloud.cn/" target="_blank" title="UCloud">
                <img src="/public/static/start/img/1px.png" data-original="http://img.ui.cn/data/link/201608/1470974880_860.png" class="imgloadinglater" alt="UCloud" rel="nofollow"></a></li>
        </ul>

        <ul class="text cl">
            <li><a href="http://www.h5-share.com" target="_blank" title="H5案例分享">H5案例分享</a></li>
            <li><a href="http://www.huoming.com/" target="_blank" title="商标注册">商标注册</a></li>
            <li><a href="http://www.chuangkit.com" target="_blank" title="创客贴在线设计">创客贴在线设计</a></li>
            <li><a href="http://www.fotor.com.cn" target="_blank" title="Fotor设计">Fotor设计</a></li>
            <li><a href="http://www.ukong.cn/" target="_blank" title="云风启志教育学院">云风启志教育学院</a></li>
            <li><a href="http://www.vchello.com/" target="_blank" title="微投网">微投网</a></li>
            <li><a href="http://www.ih5.cn/" target="_blank" title="互动大师">互动大师</a></li>
            <li><a href="http://www.opencom.cn/?from=UIZG" target="_blank" title="OpenCom">OpenCom</a></li>
            <li><a href="http://techbrood.com" target="_blank" title="踏得网">踏得网</a></li>
            <li><a href="http://11space.com" target="_blank" title="11space">11space</a></li>
            <li><a href="http://www.35pic.com/" target="_blank" title="千广网">千广网</a></li>
            <li><a href="http://588ku.com/" target="_blank" title="素材库">素材库</a></li>
            <li><a href="http://www.visionunion.com/" target="_blank" title="视觉同盟">视觉同盟</a></li>
            <li><a href="https://www.bugtags.com" target="_blank" title="Bugtags">Bugtags</a></li>
            <li><a href="http://www.ps2h5.com/" target="_blank" title="屏面">屏面</a></li>
            <li><a href="http://699pic.com" target="_blank" title="摄图网">摄图网</a></li>
            <li><a href="http://www.uisdc.com/" target="_blank" title="优设">优设</a></li>
            <li><a href="http://www.genduiren.cn/" target="_blank" title="跟对人">跟对人</a></li>
            <li><a href="http://www.weiye.me" target="_blank" title="H5页面制作">H5页面制作</a></li>
            <li><a href="http://cn.cmcm.com/" target="_blank" title="猎豹移动">猎豹移动</a></li>
        </ul>

    </div>-->
    <!--  -->
</div>
<!--  -->


<script src="/public/static/start/js/jquery.nivo.slider.js"></script>
<script type="text/javascript">
    $('#slider').nivoSlider({
        effect: 'boxRandom', // 效果
        animSpeed: 500, // 动画速度
        pauseTime: 3000, // 暂停时间
        slices: 10, // 分为10列
    });
    // 设置小圆点偏移值，使居中
    var sliderOffset = $(".nivo-controlNav").width() * (-0.5) - 16;
    $(".nivo-controlNav").css("marginLeft", sliderOffset);

    //下载附件
    //p-down0: 已验证注册用户
    //p-down1: 未登录用户
    //p-down2: 未验证用户，可下载
    //p-down3: 未验证用户，不可下载

    $("a[name='p-down0']").click(function () {
        var url = $(this).attr("src");
        globalTip({'msg': '正在为您准备下载', 'setTime': 2});
        updateTime();
        window.open(url);
        return false;
    });

    $("a[name='p-down1']").click(function () {
        globalTip({'msg': '请登录后再下载素材', 'setTime': 3, 'URL': 'http://ui.cn/login.html', 'jump': true});
        return false;
    });

    $("a[name='p-down2']").click(function () {
        var url = $(this).attr("src");
        globalTip({'msg': '您的邮箱或电话还未进行验证，每小时仅可下载1个素材', 'setTime': 3});
        updateTime();
        window.open(url);
        return false;
    });

    $("a[name='p-down3']").click(function () {
        globalTip({'msg': '您的邮箱或电话还未进行验证，每小时仅可下载1个素材', 'setTime': 3, 'URL': 'http://account.ui.cn/accountinfo.html', 'jump': true});
        return false;
    });

    function updateTime() {
        $.ajax({
            url: "http://ui.cn/updateLastDownloadTime",
            type: 'post',
            data: {
                time: 0},
            dataType: 'JSONP',
            success: function (data) {
                globalTip({"msg": "开始下载", "setTime": 3});
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                globalTip({"msg": "下载错误,请重试！", "setTime": 3});
            },
        });
    }

</script>
<script src="/public/static/start/js/new-ad.js"></script>
<script src="/public/static/start/js/recommend.js"></script>
<script type="text/javascript" src="/public/static/start/js/danmu.js"></script>
<!--  -->
<div class="ft-wp">
    <div class="wpn cl">
        <div class="ft cl">
            <i class="icon-uimini"></i>
            <div class="ft-info">
                <ul class="ft-nav cl">
                    <li><a href="http://www.ui.cn/about.html#section-3" target="_blank">商务合作</a></li>
                    <li><a href="http://qa.ui.cn/" target="_blank">意见反馈</a></li>
                    <li><a href="http://www.ui.cn/about.html#section-0" target="_blank">关于我们</a></li>
                    <li><a href="http://www.ui.cn/about.html#section-3" target="_blank">联系我们</a></li>
                    <li><a href="http://www.ui.cn/about.html#section-1" target="_blank">版权声明</a></li>
                    <li><a href="http://www.ui.cn/about.html#section-2" target="_blank">隐私保护</a></li>
                </ul>
                <p class="copy"><a href="http://www.miibeian.gov.cn/">京ICP备14007358号-1</a> / 京公网安备11010802014104号 / Powered by &copy; 2008-2017 UI.CN</p>
                <div class="ft-share cl">
                    <a id="ft-wx" class="share iconfont pos" title="微信" href="javascript:;" target="_blank" rel="nofollow">
                        <i class="icon-weixin1"></i>
                        <div class="ft-wx-show"></div>
                    </a>

                    <a class="share iconfont pos" title="新浪微博" href="http://www.weibo.com/iconfans" target="_blank" rel="nofollow">
                        <i class="icon-sina"></i>
                    </a>
                    <a class="share iconfont pos" title="腾讯QQ" href="http://wpa.qq.com/msgrd?v=3&uin=1369535553&site=qq&menu=yes" target="_blank" rel="nofollow">
                        <i class="icon-qq"></i>
                    </a>

                    <a class="anquan" rel="nofollow" key ="544db0a1efbfb029d492a013" logo_size="83x30" logo_type="business" href="http://www.anquan.org" >
                    </a>

                    <style>
                        #kx_verify_link { width: 79px; height: 28px; }
                    </style>
                    <span class="kexin" id="kx_verify"></span>
                    <script type="text/javascript">
                        (function () {
                            var _kxs = document.createElement('script');
                            _kxs.id = 'kx_script';
                            _kxs.async = true;
                            _kxs.setAttribute('cid', 'kx_verify');
                            _kxs.src = ('https:' == document.location.protocol ? 'https://ss.knet.cn' : 'http://rr.knet.cn') + '/static//public/static/start/js/icon3.js?sn=e14110511010655874a7k9000000&tp=icon3';
                            _kxs.setAttribute('size', 2);
                            var _kx = document.getElementById('kx_verify');
                            _kx.parentNode.insertBefore(_kxs, _kx);
                        })();
                    </script>
                    <!--   <a class="qinu" style="width:72px;height:30px;float: left;margin: 5px;" href="http://www.qiniu.com/" target="blank_">
                        <img src="/public/static/start/img/qiniu.png" alt="">
                    </a> -->
                </div>
            </div>
        </div><!-- ft -->
    </div><!-- wpn -->
</div><!-- ft-wp -->

<!--显示天气-->
<script>
    (function(T,h,i,n,k,P,a,g,e){
        g=function(){P=h.createElement(i);a=h.getElementsByTagName(i)[0];P.src=k;P.charset="utf-8";
        P.async=1;a.parentNode.insertBefore(P,a)};
        T["ThinkPageWeatherWidgetObject"]=n;
        T[n]||(T[n]=function(){(T[n].q=T[n].q||[]).push(arguments)});
        T[n].l=+new Date();if(T.attachEvent){T.attachEvent("onload",g)}
        else{T.addEventListener("load",g,false)}}(window,document,"script","tpwidget","//widget.thinkpage.cn/widget/chameleon.js"))
</script>
<script>
    tpwidget("init", {
    "flavor": "bubble",
    "location": "WS0E9D8WN298",
    "geolocation": "enabled",
    "position": "top-right",
    "margin": "10px 10px",
    "language": "zh-chs",
    "unit": "c",
    "theme": "chameleon",
    "uid": "U5CF7D3258",
    "hash": "fc5365d0b1ff7b58755fbe03b83a3722"
});
tpwidget("show");
</script>

<div id="scrollUp" class="scrollup">
    <a class="arrows" title="返回顶部" href="javascript:;"></a>
    <a class="feedback" title="反馈" target="_blank" href="http://qa.ui.cn/"><em>反馈</em></a>
</div>
<div id="captcha" style="width: 300px;position: fixed;left: 50%;top:50%;z-index: 9999999;"></div>
</body>
<script src="/public/static/public/js/js_msg.js"></script>
<script type="text/javascript" src="/public/static/start/js/form.js"></script>
</html>