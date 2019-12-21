<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    //定义商品-商品模型关联，一个商品属于一个商品模型
    public function typeBind()
    {
        return $this->belongsTo('Type', 'type_id', 'id')->bind('type_name');
    }

    public function type()
    {
        return $this->belongsTo('Type', 'type_id', 'id');
    }

    //定义商品-商品品牌关联，一个商品属于一个品牌
    public function brandBind()
    {
        return $this->belongsTo('Brand', 'brand_id', 'id')->bind(['brand_name' => 'name']);
    }

    public function brand()
    {
        return $this->belongsTo('Brand', 'brand_id', 'id');
    }

    //定义商品-商品分类关联，一个商品属于一个上平分类
    public function categoryBind()
    {
        return $this->belongsTo('Category', 'cate_id', 'id')->bind('cate_name');
    }

    public function category()
    {
        return $this->belongsTo('Category', 'cate_id', 'id');
    }

    //定义商品-相册图片关联，一个商品有多个图片
    public function goodsImages()
    {
        return $this->hasMany('GoodsImages', 'goods_id', 'id');
    }

    //定义商品-规格商品SKU的关联，一个商品SPU 有多个规格商品SKU
    public function specGoods()
    {
        return $this->hasMany('SpecGoods', 'goods_id', 'id');
    }

    //获取器 对goods_attr字段进行转化
    public function getGoodsAttrAttr($value)
    {
        return $value ? json_decode($value, true) : [];
    }
}
