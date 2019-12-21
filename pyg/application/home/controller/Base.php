<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //查询所有分类
        $this->getCategory();
    }
    //查询所有分类，父子树状结构
    public function getCategory(){
        //先尝试读取缓存
        $category=cache('category');
        if(empty($category)){
            //查询所有分类
            $category=\app\common\model\Category::select();
            //转为标准的二维数组
            $category=(new \think\Collection($category))->toArray();
            //转为父子树状结构
            $category=get_tree_list($category);
            //存储到缓存
            cache('category',$category,86400);
        }
        //变量赋值
        $this->assign('category',$category);
    }
}
