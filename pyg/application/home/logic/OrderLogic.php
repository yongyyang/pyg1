<?php
namespace app\home\logic;
class OrderLogic{
    //查询选中的购物记录及商品信息
    public static function getCartWithGoods(){
        //关联模型查询 购物车、商品、SKU
        $user_id=session('user_info.id');
        $cart_data=\app\common\model\Cart::with('goods,spec_goods')->where('user_id',$user_id)->where('is_selected',1)->select();
        //使用SKU的价格，库存覆盖商品价格库存
        //声明变量记录所选商品总数量
        $total_number=0;
        //声明变量记录所选商品价格
        $total_price=0;
        $cart_data=(new \think\Collection($cart_data))->toArray();
        foreach ($cart_data as $k=>&$v){
            if($v['spec_goods_id']){
                $cart_data[$k]['goods']['goods_price']=$v['spec_goods']['price'];
                $cart_data[$k]['goods']['cost_price']=$v['spec_goods']['cost_price'];
                $cart_data[$k]['goods']['goods_number']=$v['spec_goods']['store_count'];
                $cart_data[$k]['goods']['frozen_number']=$v['spec_goods']['store_frozen'];
            }
            //累计计算总数量和总金额
            $total_number+=$v['number'];
            $total_price+=$v['number']*$v['goods']['goods_price'];
        }
//        return [
//            'cart_data'=>$cart_data,
//            'total_number'=>$total_number,
//            'total_price'=>$total_price
//        ];
        return compact('cart_data','total_number','total_price');
    }
}