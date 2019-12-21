<?php

namespace app\common\model;

use think\Model;

class Brand extends Model
{
    //定义品牌-分类关联关系
    public function category(){
        return $this->belongsTo('Category','cate_id','id');
    }
    public function categoryBind(){
        return $this->belongsTo('Category','cate_id','id')->bind('cate_name');
    }
}
