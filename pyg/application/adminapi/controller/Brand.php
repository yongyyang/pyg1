<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Brand extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数
        $params = input();
        $where = [];
        if (isset($params['cate_id'])) {
            //指定分类下的品牌列表
            $where['cate_id'] = $params['cate_id'];
            $list = \app\common\model\Brand::field('id,name')->where($where)->select();
        } else {
            //分页加搜索
            if (isset($params['keyword']) && !empty($params['keyword'])) {
                $keyword = $params['keyword'];
                $where['t1.name'] = ['like', "%$keyword%"];
            }
            $list = \app\common\model\Brand::alias('t1')->join('category t2', 't1.cate_id=t2.id', 'left')->field('t1.id,t1.name,t1.logo,t1.desc,t1.is_hot,t1.sort,t2.cate_name')->where($where)->paginate(10);
        }
        //返回数据
        $this->ok($list);
    }


    /**
     * 保存新建的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'name' => 'require',
            'cate_id' => 'require|integer|>:0',
            'is_hot' => 'require|in:0,1',
            'sort' => 'require|between:0,9999'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //生成缩略图
        if (isset($params['logo']) && !empty($params['logo']) && is_file($params['logo'])) {
            \think\Image::open('.' . $params['logo'])->thumb(200, 100)->save('.' . $params['logo']);
        }
        //添加数据
        $brand = \app\common\model\Brand::create($params, true);
        $info = \app\common\model\Brand::find($brand['id']);
        //返回数据
        $this->ok($info);
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询一条数据
        $info = \app\common\model\Brand::find($id);
        $this->ok($info);
    }


    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'name|品牌名称' => 'require',
            'cate_id' => 'require|integer|>:0',
            'is_hot' => 'require|in:0,1',
            'sort' => 'require|between:0,9999'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //生成缩略图
        if (isset($params['logo']) && !empty($params['logo']) && is_file($params['logo'])) {
            \think\Image::open('.'.$params['logo'])->thumb(200,100)->save('.'.$params['logo']);
        }
        //更新数据
        \app\common\model\Brand::update($params,['id'=>$id],true);
        $info=\app\common\model\Brand::find($id);
        //返回数据
        $this->ok($info);
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //品牌下有商品，不允许删除
        $total=\app\common\model\Goods::where('brand_id',$id)->count();
        if($total>0){
            $this->fail('品牌下有商品，不允许删除');
        }
        //删除品牌
        \app\common\model\Brand::destroy($id);
        //返回结果
        $this->ok();
    }
}
