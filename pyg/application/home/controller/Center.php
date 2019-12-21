<?php

namespace app\home\controller;

use think\Controller;
use think\Cookie;

class Center extends Base
{
    public function footMark()
    {
        $goods_id = cookie('ids');
        $goods_id = array_unique(array_filter(explode(',', $goods_id)));
        $list=[];
        foreach ($goods_id as $v) {
            $good = \app\common\model\Goods::where('id', (int)$v)->find();
            $list[]=$good;
        }
        $total = count($goods_id);
//    dump($list);  die;
        return view('footmark', ['total' => $total, 'list' => $list]);
    }

    public function info()
    {
        $user_id = session('user_info.id');
        $user = \app\common\model\User::where('id', $user_id)->find();
        $userinfo['nickname'] = $user->nickname;
        $userinfo['gender'] = $user->gender;
        $userinfo['year'] = substr($user->birthday, 0, 4);
        $userinfo['month'] = substr($user->birthday, 4, 2);
        $userinfo['date'] = substr($user->birthday, 6, 2);
        if ($user->address) {
            $address = explode(' ', $user->address);
            $userinfo['province'] = $address[0];
            $userinfo['city'] = $address[1];
            $userinfo['district'] = $address[2];
        } else {
            $userinfo['province'] = $userinfo['city'] = $userinfo['district'] = '';
        }
        $userinfo['job'] = $user->job;
        $userinfo['logo'] = $user->logo;
        return view('info', ['userinfo' => $userinfo]);
    }

    public function edit()
    {
        $id = session('user_info.id');
        $params = input();
        $nickname = $params['nickname'];
        $gender = $params['gender'];
        if ((int)$params['month'] < 10) {
            $month = '0' . $params['month'];
        } else {
            $month = $params['month'];
        }
        if ((int)$params['date'] < 10) {
            $date = '0' . $params['date'];
        } else {
            $date = $params['date'];
        }
        $birthday = $params['year'] . $month . $date;
        $address = $params['province'] . " " . $params['city'] . " " . $params['district'];
        $job = $params['job'];
        \app\common\model\User::where('id', $id)->update([
            'nickname' => $nickname,
            'gender' => $gender,
            'birthday' => $birthday,
            'address' => $address,
            'job' => $job
        ]);
        $this->success('个人信息修改成功', "footmark");
    }

    public function logo()
    {
        $id = session('user_info.id');
        $file = $_FILES['logo'];
        $filename = $file['name'];
        move_uploaded_file($file['tmp_name'], ROOT_PATH . 'public' . DS . 'uploads' . DS . 'users' . DS . $filename);
        $logo = DS . 'uploads' . DS . 'users' . DS . $filename;
        \app\common\model\User::where('id', $id)->update(['logo' => $logo]);
    }

    public function save()
    {
        $user_id = session('user_info.id');
        $user = \app\common\model\User::where('id', $user_id)->find();
        $username = $user->username;
        $phone = $user->phone;
        return view('safe', ['username' => $username, 'phone' => $phone]);
    }

    public function editPassword()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'old_password|旧密码' => 'require|length:6,20',
            'new_password|新密码' => 'require|length:6,20|confirm:confirm_password',
        ]);
        if ($validate !== true) {
            $this->error('密码格式不正确');
        }
        $user_id = session('user_info.id');
        $user = \app\common\model\User::where('id', $user_id)->find();
        $password = encrypt_password($params['old_password']);
        if ($password !== $user->password) {
            $this->error('旧密码不正确');
        }
        if ($params['new_password'] == $params['old_password']) {
            $this->error('新旧密码不能相同');
        }
        if ($params['confirm_password'] != $params['new_password']) {
            $this->error('确认密码要与新密码相同');
        }
        $user_id = session('user_info.id');
        $user = \app\common\model\User::find($user_id);
        $user->password = encrypt_password($params['new_password']);
        $user->save();
        $this->redirect('home/login/login');
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

    public function phonecheck()
    {
        //接收参数
        $params = input();
        $validate = $this->validate($params, [
            'captcha' => 'require|captcha',
            'code' => 'require',
        ]);
        if ($validate !== true) {
            $res = ['code' => 400, 'msg' => $validate];
            echo json_encode($res);
            die;
        }
        //验证码校验
        $code = cache('register_code_' . $params['phone']);
        if ($code != $params['code']) {
            $res = ['code' => 400, 'msg' => '验证码错误'];
            echo json_encode($res);
            die;
        }
        $res = ['code' => 200, 'msg' => '手机验证通过'];
        echo json_encode($res);
        die;
    }

    public function phonechange()
    {
        //接收参数
        $params = input();
        $validate = $this->validate($params, [
            'phone' => 'require',
            'code' => 'require',
        ]);
        if ($validate !== true) {
            $res = ['code' => 400, 'msg' => $validate];
            echo json_encode($res);
            die;
        }
        //验证码校验
        $code = cache('register_code_' . $params['phone']);
        if ($code != $params['code']) {
            $res = ['code' => 400, 'msg' => '验证码错误'];
            echo json_encode($res);
            die;
        }
        $phone = $params['phone'];
        $user_id = session('user_info.id');
        $user = \app\common\model\User::where('id', $user_id)->find();
        $user->phone = $phone;
        $user->save();
        $res = ['code' => 200, 'msg' => '手机验证通过'];
        echo json_encode($res);
        die;
    }
    public function address(){
        $user_id=session('user_info.id');
        $list=\app\common\model\Address::where('user_id',$user_id)->select();
        return view('address',['list'=>$list]);
    }
}