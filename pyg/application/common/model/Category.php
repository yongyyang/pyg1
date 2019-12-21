<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    //定义分类-品牌关联  一个分类有对个品牌
    public function brands()
    {
        return $this->hasMany('Brand', 'cate_id', 'id');
    }

    //获取器  对pid_path进行转化
    public function getPidPathAttr($value)
    {
        return $value ? explode('_', $value) : [];
    }
}
