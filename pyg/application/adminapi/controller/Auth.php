<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Auth extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数
        $params = input();
        //查询数据
        $list = \app\common\model\Auth::select();
        //先转化为标准的二维数组
        $list = (new \think\Collection($list))->toArray();
        if (isset($params['type']) && $params['type'] == 'tree') {
            //转为父子级树状列表结构
            $list = get_tree_list($list);
        } else {
            //转为无限级分类列表结构
            $list = get_cate_list($list);
        }
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
        $params = $request->param();
        //参数检测
        $validate = $this->validate($params, [
            'auth_name|权限名称' => 'require',
            'pid|上级权限' => 'require|integer|>=:0',
            'is_nav|是否菜单权限' => 'require|in:0,1'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //处理level和pid_path
        if ($params['pid'] == 0) {
            //添加的是顶级权限
            $params['level'] = 0;
            $params['pid'] = 0;
        } else {
            //根据pid查询上级权限
            $p_info = \app\common\model\Auth::find($params['pid']);
            if (!$p_info) {
                $this->fail('数据异常');
            }
            $params['level'] = $p_info['level'] + 1;
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
        }
        //添加数据
        $auth = \app\common\model\Auth::create($params, true);
        //返回数据
        $this->ok($auth);
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
        $auth = \app\common\model\Auth::field('id,auth_name,pid,pid_path,auth_c,auth_a,is_nav,level')->find($id);
        //返回数据
        $this->ok($auth);
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
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'auth_name' => 'require',
            'pid' => 'require|integer|>=:0',
            'is_nav' => 'require|in:0,1'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //处理level和pid_path
        if ($params['pid'] == 0) {
            $params['level'] = 0;
            $params['pid_path'] = 0;
        } else {
            $p_info = \app\common\model\Auth::find($params['pid']);
            if (!$p_info) {
                $this->fail('数据异常');
            }
            $params['level'] = $p_info['level'] + 1;
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
            //不能升降级，先查询原来的级别，和要修改的级别进行对比
            $info = \app\common\model\Auth::find($id);
            if (!$info) {
                $this->fail('数据异常');
            }
            if ($info['level'] != $params['level']) {
                $this->fail('不能升降级');
            }
        }
        //修改数据
        \app\common\model\Auth::update($params, ['id' => $id], true);
        //返回数据
        $info = \app\common\model\Auth::find($info);
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
        //权限下有子权限，不能删除
        $total = \app\common\model\Auth::where('pid', $id)->count('id');
        if ($total > 0) {
            $this->fail('权限下有子权限，不能删除');
        }
        //删除数据
        \app\common\model\Auth::destroy($id);
        //返回数据
        $this->ok();
    }

    //菜单权限接口
    public function nav()
    {
        //获取当前登陆的管理员用户id
        $user_id = input();
        //查询角色ID
        $info = \app\common\model\Admin::find($user_id);
        $role_id = $info['role_id'];
        //判断是否是超级管理员
        if ($role_id == 1) {
            //超级管理员、直接查询权限表，菜单权限is_nav=1
            $list = \app\common\model\Auth::where('is_nav', 1)->select();
        } else {
            //其他管理员，先查询角色表，获取role_auth_ids字段（拥有的权限id）
            $role = \app\common\model\Role::find($role_id);
            $role_auth_ids = $role['role_auth_ids'];
            $list = \app\common\model\Auth::where('id', in, $role_auth_ids)->where('is_nav', 1)->select();
        }
        //转化为父子级树状结构
        $list=(new \think\Collection($list))->toArray();
        $list=get_tree_list($list);
        //返回数据
        $this->ok($list);
    }
}
