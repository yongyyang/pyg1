<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Admin extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //分页+搜索
        //接收参数 keyword,page,page是分页组件自动接收使用
        $params = input();
        $where = [];
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $keyword = $params['keyword'];
            $where['username'] = ['like', '%keyword%'];
        }
        //连表查询
        $list = \app\common\model\Admin::alias('t1')->join('role t2', 't1.role_id=t2.id', 'left')->field('t1.*,t2.role_name')->where($where)->paginate(10);
        //返回数据
        $this->ok($list);
    }


    /**
     * 保存新建的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收参数
        $params=input();
        //参数检测
        $validate=$this->validate($params,[
            'username'=>'require|unique:admin,username',
            'email'=>'require|email',
            'role_id'=>'require|integer|>:0',
            'password'=>'length:6,20'
        ]);
        if($validate!==true){
            $this->fail($validate);
        }
        //添加数据
        if(!isset($params['password'])||empty($params['password'])){
            $params['password']='123456';
        }
        //密码加密
        //手动加密
        $params['password']=encrypt_password($params['password']);
        $admin=\app\common\model\Admin::create($params,true);
        //返回数据
        $info=\app\common\model\Admin::find($admin['id']);
        $this->ok($info);
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询数据
        $info=\app\common\model\Admin::find($id);
        //返回数据
        $this->ok($info);
    }


    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //两个功能，密码重置和修改管理员其他信息
        if($id==1){
            //超级管理员不允许修改
            $this->fail('无权修改此管理员');
        }
        //接收参数
        $param=input();
        //判断是否重置密码
        if(isset($param['type'])&&$param['type']=='reset_pwd'){
            //重置密码
            $param['password']=encrypt_password('123456');
        }else{
            //修改其他信息、邮箱、昵称、角色ID等
            $validate=$this->validate($param,[
                'email|邮箱'=>'email',
                'nickname|昵称'=>'length:2,20',
                'role_id|角色id'=>'integer|gt:0'
            ]);
            if($validate!==true){
                $this->fail($validate);
            }
            //修改其他信息，不修改密码
            unset($param['password']);

        }
        //确保不修改用户名
        unset($param['username']);
        //修改数据
        \app\common\model\Admin::update($param,['id'=>$id],true);
        //返回数据
        $info=\app\common\model\Admin::find($id);
        $this->ok($info);
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //超级管理员不能删除
        if($id==1){
            $this->fail('无权删除此管理员');
        }
        $info=\app\common\model\Admin::find($id);
        if(!$info){
            $this->ok();
        }
        //是否超级管理员
        if($info['role_id']==1){
            $this->fail('无权删除此管理员');
        }
        //不能删除自己
        if($id==input('user_id')){
            $this->fail('不能删除自己');
        }
        //删除数据
        \app\common\model\Admin::destroy($id);
        //返回数据
        $this->ok();
    }
}
