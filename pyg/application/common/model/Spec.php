<?php

namespace app\common\model;

use think\Model;

class Spec extends Model
{
    //定义规格-规格值关联，一个规格有对个规格值
    public function specValues(){
        return $this->hasMany('SpecValue','spec_id');
    }
}
