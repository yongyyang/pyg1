<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Type extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询数据
        $list = \app\common\model\Type::select();
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
            'type_name' => 'require|max:20',
            'spec' => 'require|array',
            'attr' => 'require|array'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //开启事务
        \think\Db::startTrans();
        try {
            //添加商品模型信息
            $type = \app\common\model\Type::create(['type_name' => $params['type_name']], true);
            //添加模型下的商品规格信息
            //先对$params['spec']数据进行处理
            foreach ($params['spec'] as $k => $v) {
                //$k为下标，$v为值
                if (empty($v['name'])) {
                    //如果规格名称为空，将当前整个规格名称信息删除，跳出本次循环
                    unset($params['spec'][$k]);
                    continue;
                }
                if (!is_array($v['value'])) {
                    //如果规格名称的规格值不是数组，将当前整个规格名称信息删除，跳出本次循环
                    unset($params['spec'][$k]);
                    continue;
                }
                foreach ($v['value'] as $key => $value) {
                    //将规格值中的空值删除
                    if (empty($value)) {
                        unset($params['spec'][$k]['value'][$key]);
                    }
                }
                if (empty($params['spec'][$k]['value'])) {
                    unset($params['spec'][$k]);
                    continue;
                }
            }
            //添加商品规格名称数据
            $spec_data = [];
            foreach ($params['spec'] as $k => $v) {
                $spec_data[] = [
                    'type_id' => $type['id'],
                    'spec_name' => $v['name'],
                    'sort' => $v['sort']
                ];
            }
            //批量添加规格名称
            $spec_model = new \app\common\model\Spec();
            $spec_res = $spec_model->saveAll($spec_data);
            //添加规格值数据
            $spec_value_data = [];
            foreach ($params['spec'] as $k => $v) {
                //$v是一个规格名称数组
                foreach ($v['value'] as $value) {
                    $spec_value_data[] = [
                        'spec_id' => $spec_res[$k]['id'],
                        'type_id' => $type['id'],
                        'spec_value' => $value
                    ];
                }
            }
            $spec_value_model = new \app\common\model\SpecValue();
            $spec_value_model->saveAll($spec_value_data);
            //处理属性参数
            foreach ($params['attr'] as $k => $v) {
                if (empty($v['name'])) {
                    //如果属性名称为空，则删除整条属性信息
                    unset($params['attr'][$k]);
                    continue;
                }
                if (!is_array($v['value'])) {
                    //如果属性可选值不是数组，则置为空数组
                    $v['value'] = [];
                }
                foreach ($v['value'] as $key => $value) {
                    //去除空的可选值
                    if (empty($value)) {
                        unset($params['attr'][$k]['value'][$key]);
                    }
                }
            }
            //添加属性信息到属性表
            $attr_data = [];
            foreach ($params['attr'] as $k => $v) {
                $attr_data[] = [
                    'type_id' => $type['id'],
                    'attr_name' => $v['name'],
                    'sort' => $v['sort'],
                    'attr_values' => implode(',', $v['value'])
                ];
            }
            $attr_model = new \app\common\model\Attribute();
            $attr_model->saveAll($attr_data);
            //提交事务
            \think\Db::commit();
            //返回数据
            $this->ok($type);
        } catch (\Exception $e) {
            //回滚事务
            \think\Db::rollback();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('msg:' . $msg . ';file:' . $file . ';line:' . $line);
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
        //查询模型名称等信息、模型下规格名，规格下规格值，模型下的属性信息
        $info = \app\common\model\Type::with('specs,specs.spec_values,attrs')->find($id);
        //将结果转为数组
        $info = $info ? $info->toArray() : [];
        //返回数据
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
            'type_name' => 'require|max:20',
            'spec' => 'require|array',
            'attr' => 'require|array'
        ]);
        if ($validate !== true) {
            $this->fail($validate);
        }
        //开启事务
        \think\Db::startTrans();
        try {
            //类型的名称的修改
            $type = \app\common\model\Type::update(['type_name' => $params['type_name']], ['id' => $id], true);
            foreach ($params as $k => $v) {
                if (empty($v['name'])) {
                    //如果规格名称为空，删除整条数据
                    unset($params['spec'][$k]);
                    continue;
                }
                if (!is_array($v['value'])) {
                    //如果规格不是数组，删除整条数据
                    unset($params['spec'][$k]);
                    continue;
                }
                foreach ($v['value'] as $key => $value) {
                    if (empty($value)) {
                        //去除规格值中的空值
                        unset($params['spec'][$k]['value'][$key]);
                    }
                }
                //如果规格值数组为空数组，删除整条数据
                if (empty($params['spec'][$k]['value'])) {
                    unset($params['spec'][$k]);
                    continue;
                }
            }
            //修改规格名称信息，先删除原来的数据，在新增新的数据
            \app\common\model\Spec::destroy(['type_id' => $id]);
            //组织数据
            $spec_data = [];
            foreach ($params['spec'] as $v) {
                $spec_data[] = [
                    'type_id' => $id,
                    'spec_name' => $v['name'],
                    'sort' => $v['sort'],
                ];
            }
            $spec_model = new \app\common\model\Spec();
            $spec_res = $spec_model->saveAll($spec_data);
            //修改规格值信息，先删除原来的数据，再增加新的数据
            \app\common\model\SpecValue::destroy(['type_id' => $id]);
            //组装数据
            $spec_value_data = [];
            foreach ($params['spec'] as $k => $v) {
                //内层遍历 规格值数组
                foreach ($v['value'] as $value) {
                    $spec_value_data[] = [
                        'type_id' => $id,
                        'spec_id' => $spec_res[$k]['id'],
                        'spec_value' => $value
                    ];
                }
            }
            $spec_value_model = new \app\common\model\SpecValue();
            $spec_value_model->saveAll($spec_value_data);
            //处理属性信息(去除空的属性值)
            foreach ($params['attr'] as $k => $v) {
                //如果属性名为空，删除整条记录
                if (empty($v['name'])) {
                    unset($params['attr'][$k]);
                    continue;
                }
                //如果属性值不是数组，置为空数组
                if (!is_array($v['value'])) {
                    $params['spec'][$k]['value'] = [];
                    continue;
                }
                //如果属性值数组中有空值，则删除空值
                foreach ($v['value'] as $key => $value) {
                    if (empty($value)) {
                        unset($params['spec'][$k]['value'][$key]);
                        continue;
                    }
                }
            }
            //属性的修改，先删除原来的数据，再添加新的数据
            \app\common\model\Attribute::destroy(['type_id' => $id]);
            //组装数据
            $attr_data = [];
            foreach ($params['attr'] as $k => $v) {
                $attr_data[] = [
                    'attr_name' => $v['name'],
                    'attr_values' => implode(',', $v['value']),
                    'type_id' => $id,
                    'sort' => $v['sort']
                ];
            }
            $attr_model = new \app\common\model\Attribute();
            $attr_model->saveAll($attr_data);
            //提交事务
            \think\Db::commit();
            $this->ok();
        } catch (\Exception $e) {
            //回滚事务
            \think\Db::rollback();
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('msg:' . $msg . ';file:' . $file . ';line:' . $line);
        }
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public
    function delete($id)
    {
        //判断是否有商品归属该商品类型
        $total = \app\common\model\Goods::where('type_id', $id)->count();
        if ($total > 0) {
            $this->fail('该类型被引用，不允许删除');
        }
        //开启事务
        \think\Db::startTrans();
        try {
            //删除数据(商品类型、类型下的规格名，规格下的规格值，类型下的属性)
            \app\common\model\Type::destroy($id);
            \app\common\model\Spec::destroy(['type_id' => $id]);
            \app\common\model\SpecValue::destroy(['type_id' => $id]);
            \app\common\model\Attribute::destroy(['type_id' => $id]);
            //提交事务
            \think\Db::commit();
            //返回数据
            $this->ok();
        } catch (\Exception $e) {
            //回滚事务
            \think\Db::rollback();
            $this->fail();
        }
    }
}
