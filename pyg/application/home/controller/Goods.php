<?php

namespace app\home\controller;

use think\Controller;
use think\Cookie;

class Goods extends Base
{
    public function index($id=0)
    {
        //接收参数
        $keywords=input('keywords');
        if(empty($keywords)){
            //获取指定分类下商品列表
            if(!preg_match('/^\d+$/',$id)){
                $this->error('参数错误');
            }
            //查询分类下的商品列表，分页
            $list = \app\common\model\Goods::where('cate_id', $id)->paginate(10);
            //查询分类名称
            $cate_info = \app\common\model\Category::find($id);
            $cate_name = $cate_info['cate_name'];
        }else{
            try{
                //从ES中搜索
                $list=\app\home\logic\GoodsLogic::search();
                $cate_name=$keywords;
            }catch(\Exception $e){
                $this->error('服务器异常');
            }
        }
        return view('index', ['list' => $list, 'cate_name' => $cate_name]);
    }

    public function detail($id)
    {
        //将商品id存入cookie中
        $ids=cookie('ids')?:'';
        $ids=$id.','.$ids;
        cookie('ids',$ids,86400*7);
        //查询商品数据
        $goods = \app\common\model\Goods::with('goods_images,spec_goods')->find($id);
        if (!empty($goods['spec_goods'])) {
            $goods['goods_price'] = $goods['spec_goods'][0]['price'];
        }
        //组装数据，得到$value_ids和sku的对应关系（values_id和price的映射关系数组）
        $value_ids_map = [];
        foreach ($goods['spec_goods'] as $v) {
            $value_ids_map[$v['value_ids']] = ['id' => $v['id'], 'price' => $v['price']];
        }
        //转为json格式
        $value_ids_map=json_encode($value_ids_map);
        //查询所有相关的规格名称，规格值
        $value_ids = array_column($goods['spec_goods'], 'value_ids');
        $value_ids = implode('_', $value_ids);
        $value_ids = explode('_', $value_ids);
        $value_ids = array_unique($value_ids);
        $spec_values = \app\common\model\SpecValue::with('spec_bind')->where('id', 'in', $value_ids)->select();
        //转化为方便页面展示的数据结构
        $specs = [];
        foreach ($spec_values as $v) {
            //组装规格名称数据
            $specs[$v['spec_id']] = [
                'id' => $v['spec_id'],
                'spec_name' => $v['spec_name'],
                'spec_values' => []
            ];
        }
        foreach ($spec_values as $v) {
            $specs[$v['spec_id']]['spec_values'][] = $v;
        }
        return view('detail', ['goods' => $goods, 'specs' => $specs,'value_ids_map'=>$value_ids_map]);
    }
}
