<?php

namespace app\home\controller;

use think\Controller;

class Login extends Controller
{
//    登录页面展示
    public function login()
    {
        //临时关闭模板布局
        $this->view->engine->layout(false);
        return view();
    }

    //登录表单提交
    public function dologin()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'username' => 'require',
            'password' => 'require|length:6,18'
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }
        $password = encrypt_password($params['password']);
        //用户名和密码一起查询，同时查询手机号和邮箱两个字段
        $info = \app\common\model\User::where(function ($query) use ($params) {
            $query->where('username', $params['username'])->whereOr('phone', $params['username'])->whereOr('email', $params['username']);
        })->where('password', $password)->find();
        if ($info) {
            //设置登录标志
            session('user_info', $info->toArray());
            //迁移cookie购物车数据到数据表
            \app\home\logic\CartLogic::cookieToDb();
            //页面跳转
            //从session获取跳转地址
            $back_url=session('back_url')?:'home/index/index';
            $this->redirect($back_url);
            //关联第三方用户
            $openid=session('open_id');
            if($openid){
                //关联用户
                $open_user=\app\common\model\OpenUser::where('open_type',session('open_type'))->where('openid',$openid)->find();
                $open_user->user_id=$info['id'];
                $open_user->save();
                session('open_user_id',null);
            }
            //同步昵称到用户表
            $nickname=session('open_nickname');
            if($nickname){
                \app\common\model\User::update(['nickname'=>$nickname],['id'=>$info['id']],true);
                session('open_nickname',null);
            }
            //页面跳转
            $this->redirect('home/index/index');
        } else {
            $this->error('用户名或密码错误');
        }
    }

    //退出登录
    public function logout()
    {
        //清空session
        session(null);
        //页面跳转
        $this->redirect('home/login/login');
    }

    //注册页面展示
    public function register()
    {
        //临时关闭模板布局
        $this->view->engine->layout(false);
        return view();
    }

    //手机注册 表单提交
    public function phone()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'phone|手机号码' => 'require|regex:1[3-9]\d{9}|unique:user,phone',
            'code|验证码' => 'require',
            'password|密码' => 'require|length:6,18|confirm:repassword',
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }
        //验证码校验
        $code = cache('register_code_' . $params['phone']);
        if ($code != $params['code']) {
            $this->error('验证码错误');
        }
        //验证码成功一次后失效
        cache('register_code' . $params['phone'], null);
        //添加用户
        $params['username'] = $params['phone'];
        $params['nickname'] = encrypt_phone($params['phone']);
        //密码加密
        $params['password'] = encrypt_password($params['password']);
        \app\common\model\User::create($params, true);
        //页面跳转
        $this->success('注册成功', 'login');
    }

    //发送短信验证码
    public function sendcode()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'phone' => 'require|regex:1[3-9]\d{9}'
        ]);
        if ($validate !== true) {
            return json(['code' => 400, 'msg' => $validate]);
        }
        //同一个手机号 一分钟只能发一次
        $last_time = cache('register_code_' . $params['phone']);
        if (time() - $last_time < 60) {
            return json(['code' => 500, 'msg' => '请求发送太频繁']);
            die;
        }
        //发送短信验证码
        $code = mt_rand(1000, 9999);
        $msg = '【创信】你的验证码是：' . $code . '，3分钟内有效！';
//        $res=send_msg($params['phone'],$msg);
        $res = true;
        if ($res === true) {
            //发送成功，将验证码保存在缓存
            cache('register_code_' . $params['phone'], $code, 180);
            //记录发送时间，用于发送前检测发送频率
            cache('register_code' . $params['phone'], time(), 180);
            return json(['code' => 200, 'msg' => '短信发送成功', 'data' => $code]);
        } else {
            //发送失败
            return json(['code' => 401, 'msg' => $res]);
        }
    }

    //qq回调地址
    public function qqcallback()
    {
        //参考oauth/callback.php
        require_once('./plugins/qq/API/qqConnectAPI.php');
        $qc = new \QC();
        //得到access_token和openid
        $access_token = $qc->qq_callback();
        $openid = $qc->get_openid();
        //获取用户信息（昵称）
        $qc = new \QC($access_token, $openid);
        $info = $qc->get_user_info();
//        dump($info);die;
        //接下来就是自动登录和注册流程
        //判断是否已经关联绑定用户
        $open_user = \app\common\model\OpenUser::where('open_type', 'qq')->where('openid', $openid)->find();
        if ($open_user && $open_user['user_id']) {
            //已经关联过用户，直接登录成功
            //同步用户信息到用户表
            $user = \app\common\model\User::find($open_user['user_id']);
            $user->nickname = $info['nickname'];
            $user->save();
            //设置登录标识
            session('user_info', $user->toArray());
            //迁移cookie购物车数据到数据表
            \app\home\logic\CartLogic::cookieToDb();
            //页面跳转
            //从session获取跳转地址
            $back_url=session('back_url')?:'home/index/index';
            $this->redirect($back_url);
            $this->redirect('home/index/index');
        } else {
            if (!$open_user) {
                //第一次登录，没有记录，添加一条记录到open_user表
                \app\common\model\OpenUser::create(['open_type' => 'qq', 'openid' => $openid]);
            }
            //让第三方账号去关联用户（可能是注册，可能是登录）
            //记录第三方账户到session,用于后续关联用户
            session('open_type','qq');
            session('open_nickname',$info['nickname']);
            session('open_id', $openid);
            $this->redirect('home/login/login');
        }

    }
}
