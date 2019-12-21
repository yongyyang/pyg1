<?php

namespace app\home\controller;

use think\Controller;

class Cart extends Base
{
    public function index()
    {
//        //获取cookie中所有的购物车数据，判断添加商品到购物车是否成功
//        $data=cookie('cart');
//        dump($data);die;
//        //如果cookie中的购物车数据有问题，全部删除，重新添加
//        cookie('cart',null);

        //查询所有购物记录
        $list = \app\home\logic\CartLogic::getAllCart();
        //对每一条购物记录 查询商品相关信息（包括SKU信息）
        foreach ($list as $k => $v) {
            $list[$k]['goods'] = \app\home\logic\GoodsLogic::getGoodsWithSpecGoods($v['goods_id'],$v['spec_goods_id']);
        }
//        dump($list);die;
        unset($v);
        return view('index', ['list' => $list]);
    }

    //加入购物车，表单提交
    public function addcart()
    {
        //只能是POST请求
        if (request()->isGet()) {
            //如果是get请求，直接跳转到首页
            $this->redirect('home/index/index');
        }
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'goods_id' => 'require|integer|>:0',
            'spec_goods_id' => 'integer|>:0',
            'number|购买数量' => 'require|integer|>:0'
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }
        //添加购物数据
        \app\home\logic\CartLogic::addCart($params['goods_id'], $params['spec_goods_id'], $params['number']);
        //展示添加成功结果页面
        $goods = \app\home\logic\GoodsLogic::getGoodsWithSpecGoods($params['goods_id'], $params['spec_goods_id']);
        return view('addcart', ['goods' => $goods]);
    }

    public function changenum()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require',
            'number' => 'require|integer|>:0'
        ]);
        if ($validate !== true) {
            $res = ['code' => 400, 'msg' => '参数错误'];
            echo json_encode($res);
            die;
        }
        //处理数据
        \app\home\logic\CartLogic::changeNum($params['id'], $params['number']);
        //返回数据
        $res = ['code' => 200, 'msg' => 'success'];
        echo json_encode($res);
        die;
    }

    public function delcart()
    {
        //接收参数
        $params = input();
        if (!isset($params['id']) || empty($params['id'])) {
            $res = ['code' => 400, 'msg' => '参数错误'];
            echo json_encode($res);
            die;
        }
        //处理数据
        \app\home\logic\CartLogic::delCart($params['id']);
        //返回数据
        $res = ['code' => 200, 'msg' => 'success'];
        echo json_encode($res);
        die;
    }

    public function changestatus()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require',
            'status' => 'require|in:0,1'
        ]);
        if ($validate !== true) {
            $res = ['code' => 400, 'msg' => $validate];
            echo json_encode($res);
            die;
        }
        //数据处理
        \app\home\logic\CartLogic::changeStatus($params['id'], $params['status']);
        //返回数据
        $res = ['code' => 200, 'msg' => 'success'];
        echo json_encode($res);
        die;
    }
}