<?php

namespace app\adminapi\controller;

use think\Controller;

class Login extends BaseApi
{
    //验证码图片地址接口
    public function verify()
    {
        //接收参数 无
        //参数检测 无
        //处理数据
        $uniqid = uniqid('login', true);    //获取唯一验证码标识
        $data = [
            'url' => captcha_src($uniqid),     //验证码路径
            'uniqid' => $uniqid
        ];
        //返回数据
        $this->ok($data);
    }

    //登录接口
    public function login()
    {
        //获取输入变量
        $param = input();
        $validate = $this->validate($param, [
            'username' => 'require',
            'password' => 'require',
            'code' => 'require',
            'uniqid' => 'require'
        ]);
        if ($validate !== true) {
            $this->fail($validate, 400);
        }
        //进行验证码手动验证
        if (!captcha_check($param['code'], $param['uniqid'])) {
            //验证码错误
            $this->fail('验证码错误', 400);
        }
        //处理数据
        //根据用户名密码查询管理员表
        $password = encrypt_password($param['password']);
        $info = \app\common\model\Admin::where('username', $param['username'])->where('password', $password)->find();
        if ($info) {
            //用户名密码匹配，登录成功，生成token
            $token = \tools\jwt\Token::getToken($info->id);
            $data = [
                'token' => $token,
                'user_id' => $info->id,
                'username' => $info->username,
                'password' => $info->password,
                'email' => $info->email
            ];
            //返回数据
            $this->ok($data);
        } else {
            $this->fail('用户名或密码错误', 401);
        }
    }
    //后台退出接口
    public function logout(){
        //清空token,将需要清空的token存入缓存，再次使用时，读取缓存进行判断
        $token=\tools\jwt\Token::getRequestToken();
        $delete_token=cache('delete_token')?:[];
        $delete_token[]=$token;
        cache('delete_token',$delete_token,86400);
        $this->ok();
    }
}
