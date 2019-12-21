<?php

namespace app\adminapi\controller;

use think\Controller;

class Upload extends BaseApi
{
    //单图片上传
    public function logo()
    {
        //接收参数
        $params = input();
        //参数检测
        if (!isset($params['type']) || empty($params['type'])) {
            $this->fail('参数错误');
        }
        //如果所传图片类型不是商品、分类或品牌，归类其他
        if (!in_array($params['type'], ['goods', 'category', 'brand'])) {
            $params['type'] = 'other';
        }
        //处理数据（图片上传）
        $file = request()->file('logo');
//        判断数据有效性
        if (empty($file)) {
            $this->fail('请上传图片');
        }
//        如果存储目录不存在则自动创建
        $dir = ROOT_PATH . 'public' . DS . 'uploads' . $params['type'];
        if (!is_dir($dir)) mkdir($dir);
        //检测并移动文件
        $info = $file->validate(['size' => 10 * 1024 * 1024, 'ext' => 'jpg,jpeg,png,gif', 'type' => 'image/jpeg,image/png,image/gif'])->move($dir);
        //判断结果
        if (!$info) {
            //图片上传失败
            $this->fail($file->getError());
        }
        //图片上传成功，返回数据
        $logo = '/uploads' . DS . $params['type'] . DS . $info->getSaveName();
        $this->ok($logo);
    }

    //多图片上传
    public function images()
    {
        //接收参数
        $type = input('type', 'goods');     //接收参数中图片类型，没有则默认为goods
        //参数检测
        if (!in_array($type, ['goods', 'category', 'brand'])) {
            $type = 'other';
        }
        //处理数据（多文件上传）
        $dir = ROOT_PATH . DS . 'public' . DS . 'uploads' . DS . $type;
        if (!is_dir($dir)) mkdir($dir);
        $files = request()->file('images');
        //初始化返回结果数组
        $res = [
            'success' => [],
            'error' => []
        ];
        foreach ($files as $file) {
            $info = $file->validate(['size' => 10 * 1024 * 1024, 'ext' => 'jpg,jpeg,png,gif', 'type' => 'image/jpeg,image/png,image/gif'])->move($dir);
            if (!$info) {
                $res['error'][] = [
                    'name' => $file->getInfo('name'),
                    'msg' => $file->getError()
                ];
            } else {
                $image = '/uploads' . DS . $type . DS . $info->getSaveName();
                $res['success'][] = $image;
            }
        }
        $this->ok($res);
    }
}
