<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    //一对一关联模型  一个管理员有一个档案
    public function profile(){
        return $this->hasOne('Profile','uid','id');
    }
    public function profileBind(){
        return $this->hasOne('Profile','uid','id')->bind('idnum');
    }
}
