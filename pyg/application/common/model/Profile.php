<?php

namespace app\common\model;

use think\Model;

class Profile extends Model
{
    //定义管理员-档案关联关系
    public function admin(){
        return $this->belongsTo('Admin','uid','id');
    }
    public function adminBind(){
        return $this->belongsTo('Admin','uid','id')->bind('username');
    }
}
