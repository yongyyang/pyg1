<?php

namespace app\adminapi\controller;

class Index extends BaseApi
{
    public function index()
    {
//       echo 'hello，这是默认的Index控制器index方法';die;
        //测试数据库配置
//        $goods=\think\Db::table('pyg_goods')->find();
//        dump($goods);die;
//        $this->ok(['action'=>'index']);
//        $this->fail('参数错误',401);
        //测试jwt
//        生成token
//        $token=\tools\jwt\Token::getToken(100);
//        $this->ok($token);
//        $userId=\tools\jwt\Token::getUserId('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjNmMmc1N2E5MmFhIn0.eyJpc3MiOiJodHRwOlwvXC9hZG1pbmFwaS5weWcuY29tIiwiYXVkIjoiaHR0cDpcL1wvd3d3LnB5Zy5jb20iLCJqdGkiOiIzZjJnNTdhOTJhYSIsImlhdCI6MTU3NDg1MzEyMSwibmJmIjoxNTc0ODUzMTIwLCJleHAiOjE1NzQ5Mzk1MjEsInVzZXJfaWQiOjEwMH0.8GUU4quskWngP1r93o36TftaPQT34iOZkTozMQ3uKXY');
//        $this->ok($userId);
//        $token=\tools\jwt\Token::getRequestToken();
//         $this->ok($token);
        //初始密码
//        echo encrypt_password('123456');die;
        //获取用户id
//        $user_id=request()->param('user_id');
//        dump($user_id);die;
//        $info=\app\common\model\Admin::with('profile')->find(1);
//        $info=\app\common\model\Admin::with('profile_bind')->find(1);
//        $info=\app\common\model\Profile::with('admin')->find(1);
//        $info=\app\common\model\Profile::with('admin')->select();
//        $info=\app\common\model\Profile::with('admin_bind')->select();
//        $info=\app\common\model\Category::with('brands')->find(72);
//        $info=\app\common\model\Category::with('brands')->select();
//        $info=\app\common\model\Brand::with('category')->find(1);
//        $info=\app\common\model\Brand::with('category')->select();
//        $info=\app\common\model\Brand::with('category_bind')->find(1);
        $info=\app\common\model\Brand::with('category_bind')->select();
        $this->ok($info);
    }
}
