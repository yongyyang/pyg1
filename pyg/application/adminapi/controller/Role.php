<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Role extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询所有数据
        $list=\app\common\model\Role::select();
        //遍历数组，对每一个角色，查询它的而所有权限
        foreach ($list as $k=>$v){
            if($v['id']==1){
                //超级管理员
                $where=[];
            }else{
                $where=['id'=>['in',$v['role_auth_ids']]];
            }
            //查询单个角色下拥有的权限
            $auth=\app\common\model\Auth::where($where)->select();
            //转化数组结构为树状结构
            $auth=(new \think\Collection($auth))->toArray();
            $auth=get_tree_list($auth);
            //放到结果数组$list中
            $list[$k]['role_auths']=$auth;
        }
        //返回数据
        $this->ok($list);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收数据
        $params=input();
       //参数检测
        $validate=$this->validate($params,[
            'role_name'=>'require',
            'auth_ids'=>'require'
        ]);
        if($validate!==true){
            $this->fail($validate);
        }
        //添加数据
        $params['role_auth_ids']=$params['auth_ids'];
        $role=\app\common\model\Role::create($params,true);
        $info=\app\common\model\Role::find($role['id']);
        //返回数据
        $this->ok($info);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询数据
        $info=\app\common\model\Role::field('id,role_name,desc,role_auth_ids')->find($id);
        //返回数据
        $this->ok($info);
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //接收参数
        $params=input();
        //参数检测
        $validate=$this->validate($params,[
            'role_name'=>'require',
            'auth_ids'=>'require'
        ]);
        if($validate!==true){
            $this->fail($validate);
        }
        //修改数据
        $params['role_auth_ids']=$params['auth_ids'];
        \app\common\model\Role::update($params,['id'=>$id],true);
        $info=\app\common\model\Role::find($id);
        //返回数据
        $this->ok($info);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //超级管理员角色不能删除
        if($id==1){
            $this->fail('该角色无法删除');
        }
        //如果角色下有关联管理员，不能删除
        //根据角色id查询管理员的role_id字段
        $total=\app\common\model\Admin::where('role_id',$id)->count();
        if($total>0){
            $this->fail('角色正被使用，无法删除');
        }
        //删除数据
        \app\common\model\Role::destroy($id);
        //返回数据
        $this->ok();
    }
}
