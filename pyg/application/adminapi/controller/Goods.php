<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Goods extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //搜索+分页
        //接收参数
        $params = input();
        //查询条件
        $where = [];
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $keyword = $params['keyword'];
            $where['goods_name'] = ['like', "%$keyword%"];
        }
        //关联模型查询
        $list = \app\common\model\Goods::with('type_bind,category_bind,brand_bind')->where($where)->paginate(10);
        //返回数据
        $this->ok($list);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
            'goods_name|商品名称' => 'require',
            'goods_price|商品价格' => 'require|float|>:0',
            'goods_logo|logo图片' => 'require',
            'cate_id|商品分类' => 'require|integer|>:0',
            'brand_id|商品品牌' => 'require|integer|>:0',
            'type_id|商品模型' => 'require|integer|>:0',
            'goods_images|相册图片' => 'require|array',
            'item|商品规格' => 'require|array',
            'attr|商品属性' => 'require|array'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //添加数据
        //开启事务
        \think\Db::startTrans();
        try {
            //商品表数据
            //logo图片生成缩略图
            if (is_file('.' . $params['goods_logo'])) {
                $goods_logo = dirname($params['goods_logo']) . DS . 'thumb_' . basename($params['goods_logo']);
                \think\Image::open('.' . $params['goods_logo'])->thumb(200, 240)->save('.' . $goods_logo);
                $params['goods_logo'] = $goods_logo;
            } else {
                $this->fail('商品logo图片不存在');
            }
            //商品属性转化为json格式字符串
            $params['goods_attr'] = json_encode($params['attr'], JSON_UNESCAPED_UNICODE);
            //添加商品表数据
            $goods = \app\common\model\Goods::create($params, true);
            //商品相册表数据
            $goods_images_data = [];
            foreach ($params['goods_images'] as $k => $v) {
                if (is_file('.' . $v)) {
                    continue;
                }
                //$v是一个图片路径，需要生成两张不同尺寸的缩略图，组成一整条数据
                $pics_big = dirname($v) . DS . 'thumb_800_' . basename($v);
                $pics_sma = dirname($v) . DS . 'thumb_400_' . basename($v);
                //生成缩略图
                $image = \think\Image::open('.' . $v);
                $image->thumb(800, 800)->save('.' . $pics_big);
                $image->thumb(400, 400)->save('.' . $pics_sma);
                $goods_images_data[] = [
                    'goods_id' => $goods['id'],
                    'pics_big' => $pics_big,
                    'pics_sma' => $pics_sma
                ];
            }
            //添加相册表数据
            $image_model = new \app\common\model\GoodsImages();
            $image_model->saveAll($goods_images_data);
            //组装规格商品数据
            $spec_goods = [];
            foreach ($params['item'] as $v) {
                //$v是一条sku数据，和数据表spec_goods字段对比，缺少goods_id字段
                $v['goods_id'] = $goods['id'];
                $spec_goods[] = $v;
            }
            //添加sku数据
            $spec_goods_model = new \app\common\model\SpecGoods();
            $spec_goods_model->saveAll($spec_goods);
            //提交事务
            \think\Db::commit();
            //重新查询商品数据，关联模型查询
            $info = \app\common\model\Goods::with('type_bind,brand_bind,category_bind')->find($goods['id']);
            //返回数据
            $this->ok($info);
        } catch (\Exception $e) {
            //回滚事务
            \think\Db::rollback();
            $msg = $e->getMessage();
            //返回数据
            $this->fail($msg);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询一条商品数据， 相册图片，规格商品，所属分类，所述品牌
        $info = \app\common\model\Goods::with('goods_images,spec_goods,category,brand')->find($id);
        $info['type'] = \app\common\model\Type::with('attrs,specs,specs.spec_values')->find($info['type_id']);
        //返回数据
        $this->ok($info);

    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //查询商品相关数据，相册图片，规格商品，所属分类，所述品牌
        $goods = \app\common\model\Goods::with('goods_images,category,brand,spec_goods')->find($id);
        //关联模型查询，不允许多个嵌套关联，只能有一个生效，查询所属模型及规格属性
        $goods['type'] = \app\common\model\Type::with('attrs,specs,specs.spec_values')->find($goods['type_id']);
        //查询所有的模型列表
        $type = \app\common\model\Type::select();
        //查询商品分类  用于三级联动中三个下拉列表显示
        //查询所有一级分类
        $cate_one = \app\common\model\Category::where('pid', 0)->select();
        //找到当前商品所属的一级分类id和二级分类id
        $pid_path = $goods['category']['pid_path'];
        //分类模型中设置过获取器，$pid_path[1],$pid_path[2]
        //查询商品所属一级分类下的所有二级分类
        $cate_two = \app\common\model\Category::where('pid', $pid_path[1])->select();
        //查询商品所属二级分类下的所有三级分类
        $cate_three = \app\common\model\Category::where('pid', $pid_path[2])->select();
        //返回数据
        $data = [
            'goods' => $goods,
            'type' => $type,
            'category' => [
                'cate_one' => $cate_one,
                'cate_two' => $cate_two,
                'cate_three' => $cate_three
            ]
        ];
        $this->ok($data);
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
        $param = input();
        //参数检测
        $validate = $this->validate($param, [
            'goods_name|商品名称' => 'require|max:100',
            'goods_price|商品价格' => 'require|float|gt:0',
            'goods_number|商品库存' => 'requrie|integer|gt:0',
            //此处省略很多字段
            //'goods_logo|商品logo' => 'require',
            'goods_images|商品相册' => 'array',
            'item|规格值' => 'require|array',
            'attr|属性值' => 'require|array',
            'type_id|商品模型' => 'require',
            'brand_id|商品品牌' => 'require',
            'cate_id|商品分类' => 'require',
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //添加数据
        //开启事务
        \think\Db::startTrans();
        try {
            //商品表数据
            //logo图片生成缩略图
            if (!empty($param['goods_logo']) && is_file($param['goods_logo'])) {
                //重新取名
                $goods_logo = dirname($param['goods_logo']) . DS . 'thumb_' . basename($param['goods_logo']);
                \think\Image::open('.' . $param['goods_logo'])->thumb(200, 240)->save('.' . $param['goods_logo']);
                $param['goods_logo'] = $goods_logo;
            }
            //商品属性转为json格式字符串
            $param['goods_attr'] = json_encode($param['attr'], JSON_UNESCAPED_UNICODE);
            //修改商品表数据
            \app\common\model\Goods::update($param, ['id' => $id], true);
            //商品相册表数据
            if (!empty($param['goods_images'])) {
                $goods_images_data = [];
                foreach ($param['goods_images'] as $v) {
                    if (!is_file('.' . $v)) {
                        continue;
                    }
                    //$v就是一个图片地址，需要生成两张不同尺寸的缩略图，组成一条数据
                    $pics_big = dirname($v) . DS . 'thumb_800_' . basename($v);
                    $pics_sma = dirname($v) . DS . 'thumb_400_' . basename($v);
                    //生成缩略图
                    $image = \think\Image::open('.' . $v);
                    $image->thumb(800, 800)->save('.' . $pics_big);
                    $image->thumb(400, 400)->save('.' . $pics_sma);
                    $goods_images_data[] = [
                        'goods_id' => $id,
                        'pics_big' => $pics_big,
                        'pics_sma' => $pics_sma
                    ];
                }
                $goods_images_model = new \app\common\model\GoodsImages();
                $goods_images_model->saveAll($goods_images_data);
            }
            //规格商品表数据（sku表）
            //先删除原来的数据，在添加新的数据
            \app\common\model\SpecGoods::destroy(['goods_id' => $id]);
            $spec_goods_data = [];
            foreach ($param['item'] as $v) {
                //$v 和数据表字段对比，缺少goods_id
                $v['goods_id'] = $id;
                $spec_goods_data = $v;
            }
            $spec_goods_model = \app\common\model\SpecGoods();
            $spec_goods_model->allowField(true)->saveAll($spec_goods_data);
            //提交事务
            \think\Db::commit();
            $info = \app\common\model\Goods::with('type_bind,brand_bind,category_bind')->find($id);
            $this->ok($info);
        } catch (\Exception $e) {
            //回滚事务
            \think\Db::rollback();
            $msg=$e->getMessage();
            $this->fail($msg);
        }
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //上架的商品必须先下架才能删除
        $goods = \app\common\model\Goods::find($id);
        if (empty($goods)) {
            $this->fail('数据异常，商品已经不存在');
        }
        if ($goods['is_on_sale']) {
            //商品已经上架，不能删除
            $this->fail('商品已经上架，不能删除');
        }
        //删除数据表数据
        \app\common\model\Goods::destroy($id);
        //查询相册图片
        $goods_images = \app\common\model\GoodsImages::where('goods_id', $id)->select();
        //删除商品相册表数据
        \app\common\model\GoodsImages::destroy(['goods_id' => $id]);
        //从硬盘删除图片
        $images = [];
        foreach ($goods_images as $v) {
            $images[] = $v['pics_big'];
            $images[] = $v['pics_sma'];
        }
        foreach ($images as $v) {
            if (is_file('.' . $v)) {
                unlink('.' . $v);
            }
        }
        $this->ok();
    }
}
