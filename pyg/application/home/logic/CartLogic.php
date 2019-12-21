<?php

namespace app\home\logic;

use app\common\model\Cart;

class CartLogic
{
    /**
     * 加入购物车
     * @param $goods_id 商品id
     * @param $spec_goods_id 规格商品SKU的id
     * @param $number 购买数量
     * @param int $is_selected 选中状态，默认选中，值为1
     */
    public static function addCart($goods_id, $spec_goods_id, $number, $is_selected = 1)
    {
        //判断登录状态，已经登录，添加到数据表，没有登录，添加到COOKIE
        if (session('?user_info')) {
            //已经登录
            $user_id = session('user_info.id');
            //判断是否已经存在相同规格商品记录（条件，用户id,商品id,规格商品id都相同）
            $where = ['user_id' => $user_id, 'goods_id' => $goods_id, 'spec_goods_id' => $spec_goods_id];
            $info = \app\common\model\Cart::where($where)->find();
            if ($info) {
                //存在相同记录，累加购买数量number
                $info->number += $number;
                $info->is_selected = $is_selected;
                $info->save();
            } else {
                //不存在，添加新纪录
                $where['number'] = $number;
                $where['is_selected'] = $is_selected;
                \app\common\model\Cart::create($where, true);
            }
        } else {
            //未登录，添加到cookie
            //去除cookie已有数据
            $data = cookie('cart') ?: [];
            //拼接当前数据下表
            $key = $goods_id . '_' . $spec_goods_id;
            //判断是否存在相同记录
            if (isset($data[$key])) {
                //存在，累加数量
                $data[$key]['number'] += $number;
                $data[$key]['is_selected'] = $is_selected;
            } else {
                //不存在，添加新的记录
                $data[$key] = ['id' => $key, 'goods_id' => $goods_id, 'spec_goods_id' => $spec_goods_id, 'number' => $number, 'is_selected' => $is_selected];
            }
            //重新保存到cookie
            cookie('cart', $data, 86400 * 7);
        }
    }

    //获取所有购物记录
    public static function getAllCart()
    {
        //判断登录状态，已经登录，查询数据吧，未登录，取cookie
        if (session('user_info')) {
            //已经登录，查询数据表
            $user_id = session('user_info.id');
            $data = Cart::field('id,user_id,goods_id,spec_goods_id,number,is_selected')->where('user_id', $user_id)->select();
            //转化为标准的二维数组
            $data = (new \think\Collection($data))->toArray();
        } else {
            //未登录 取cookie
            $data = cookie('cart') ?: [];
            //转化为标准的二维数组（去掉字符串下标）
            $data = array_values($data);
        }
        return $data;
    }

    //登录后将COOKIE购物车数据迁移到数据表
    public static function cookieToDb()
    {
        //从cookie取出所有数据
        $data = cookie('cart') ?: [];
        //将数据添加到数据表
        foreach ($data as $v) {
            self::addCart($v['goods_id'], $v['spec_goods_id'], $v['number']);
        }
        //清空cookie购物车数据
        cookie('cart', null);
    }

    public static function changeNum($id, $number)
    {
        //判断登录状态，一登录修改数据表，未登录修改cookie
        if (session('?user_info')) {
            $user_id = session('user_info.id');
            //只能修改当前用户自己的记录
            Cart::update(['number' => $number], ['id' => $id]);
        } else {
            //先从cookie去除所有记录
            $data = cookie('cart') ?: [];
            //修改数量
            $data[$id]['number'] = $number;
            //重新保存到cookie
            cookie('cart', $data, 86400 * 7);
        }
    }

    public static function delCart($id)
    {
        //判断登录状态，已经登录删除数据表，没有登录删除cookie对应数据
        if (session('?user_info')) {
            //已经登录，删除数据表对应数据
            $user_id = session('user_info.id');
            Cart::where(['id' => $id, 'user_id' => $user_id])->delete();
        } else {
            //没有登录，删除COOKIE对应数据
            //从cookie取出所有数据
            $data = cookie('cart') ?: [];
            //$id就是下标
            unset($data[$id]);
            //重新保存cookie
            cookie('cart', $data, 86400 * 7);
        }
    }

    //修改选中状态
    public static function changeStatus($id, $status)
    {
        //登录状态检测，已经登录修改数据表，没有登录，修改cookie
        if (session('?user_info')) {
            //已经登录 修改数据表
            $user_id = session('user_info.id');
            $where['user_id'] = $user_id;
            if ($id != 'all') {
                //引起状态改变的不是全选标签
                $where['id'] = $id;
            }
            Cart::where($where)->update(['is_selected' => $status]);
        } else {
            //没有登录  修改COOKIE
            //取出cookie所有购物车数据
            $data = cookie('cart') ?: [];
            //$id就是一个下标
            if ($id == 'all') {
                //全部修改
                foreach ($data as $v) {
                    $v['is_selected'] = $status;
                }
                unset($v);
            } else {
                //修改一个
                $data[$id]['is_selected'] = $status;
            }
            //重新保存cookie
            cookie('cart', $data, 86400 * 7);
        }
    }
}