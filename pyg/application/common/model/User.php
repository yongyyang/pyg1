<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
    //针对昵称存在特殊字符（如表情符号等的转码与解码）
    //修改器
//    public function setNickNameAttr($value){
//        return urlencode($value);
//    }
//    //获取器
//    public function getNickNameAttr($value){
//        return urldecode($value);
//    }
}
