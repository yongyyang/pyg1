<?php

namespace app\adminapi\logic;
class AuthLogic
{
    //权限检测
    public static function check()
    {
        //判断是否特殊页面（如首页，登出等，所有角色均可访问，无需检测）
        $controller = request()->controller();
        $action = request()->action();
        $path = strtolower($controller . '/' . $action);
        if (in_array($path, ['index/index', 'login/logout'])) {
            //特殊页面，无需检测
            return true;
        }
        //获取管理员的角色id
        $user_id = input('user_id');
        //判断是否是超级管理员
        $admin = \app\common\model\Admin::find($user_id);
        $role_id = $admin['role_id'];
        if ($role_id == 1) {
            //超级管理员，所有页面都可访问，无需检测
            return true;
        }
        //查询当前管理员所用的权限（从角色表查询对应的role_auth_ids）
        $role = \app\common\model\Role::find($role_id);
        $role_auth_ids = $role['role_auth_ids'];
        //根据当前访问的控制器，方法查询到具体的权限id
        $auth = \app\common\model\Auth::where('auth_c', strtolower($controller))->where('auth_a', $action)->find();
        $auth_id = $auth['id'];
        //判断当前要访问的权限id是否在role_auth_ids中
        if (in_array($auth_id, $role_auth_ids)) {
            return true;
        }
        return false;
    }
}