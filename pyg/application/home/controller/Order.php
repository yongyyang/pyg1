<?php

namespace app\home\controller;

use think\Controller;
use think\Exception;
use think\Request;

class Order extends Base
{
    /**
     * 显示结算页面
     *
     * @return \think\Response
     */
    public function create()
    {
        //登录检测
        if (!session('?user_info')) {
            //没有登录跳转到登录界面
            //设置登录后跳转的地址
            session('back_url', 'home/cart/index');
            $this->redirect('home/login/login');
        }
        //获取收货地址信息
        //获取用户id
        $user_id = session('user_info.id');
        $address = \app\common\model\Address::where('user_id', $user_id)->select();
        //查询选中的购物记录
        $res = \app\home\logic\OrderLogic::getCartWithGoods();
//        $cart_data = $res['cart_data'];
//        $total_number = $res['total_number'];
//        $total_price = $res['total_price'];
//        return view('create', ['cart_data' => $cart_data, 'total_number' => $total_number, 'total_price' => $total_price, 'address' => $address]);
        $res['address'] = $address;
        return view('create', $res);
    }

    /**
     * 创建订单
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
            'address_id' => 'require|integer|>:0'
        ]);
        if (!$validate) {
            $this->error($validate);
        }
        //查询收货地址信息
        $user_id = session('user_info.id');
        $address = \app\common\model\Address::where('user_id', $user_id)->where('id', $params['address_id'])->find();
        if (!$address) {
            $this->error('收货地址数据异常');
        }
        //开启事务
        \think\Db::startTrans();
        try {
            //向订单表添加一条数据
            //订单编号，纯数字或者字母加数字
            $order_sn = time() . mt_rand(100000, 999999);
            //查询选中的购物记录，并计算商品总金额
            $res = \app\home\logic\OrderLogic::getCartWithGoods();
            //库存检测
            foreach ($res['cart_data'] as $v) {
                //购买数量$v['number'], 库存$v['goods']['goods_number']
                if ($v['number'] > $v['goods']['goods_number']) {
                    //订单中存在库存不足的商品
                    //直接抛出异常，进入catch
                    throw new Exception('订单中存在库存不足的商品');
                }
            }
            //组装一条订单数据
            $row = [
                'user_id' => $user_id,
                'order_sn' => $order_sn,
                'order_status' => 0,
                'consignee' => $address['consignee'],
                'phone' => $address['phone'],
                'address' => $address['area'] . $address['address'],
                'goods_price' => $res['total_price'],    //商品总价
                'shipping_price' => 0,    //邮费
                'coupon_price' => 0,    //优惠券抵扣金额
                'order_amount' => $res['total_price'],    //实付金额
                'total_amount' => $res['total_price']     //应付金额
            ];
            //向订单表添加一条数据
            $order = \app\common\model\Order::create($row, true);
            //向订单商品表添加多条数据
            $order_goods = [];
            foreach ($res['cart_data'] as $v) {
                //将每条记录添加到订单商品表
                $order_goods[] = [
                    'order_id' => $order['id'],
                    'goods_id' => $v['goods_id'],
                    'spec_goods_id' => $v['spec_goods_id'],
                    'number' => $v['number'],
                    'goods_name' => $v['goods']['goods_name'],
                    'goods_logo' => $v['goods']['goods_logo'],
                    'goods_price' => $v['goods']['goods_price'],
                    'spec_value_names' => $v['spec_goods']['value_names']
                ];
            }
            //批量添加数据
            $order_goods_model = new \app\common\model\OrderGoods();
            $order_goods_model->saveAll($order_goods);
            //从购物车删除对应记录
            //\app\common\model\Cart::destroy(['user_id'=>$user_id,'is_selected'=>1])
            //冻结库存
            $goods = [];
            $spec_goods = [];
            foreach ($res['cart_data'] as $v) {
                //购买数量 $v['number']  商品id $v['goods_id']  规格商品$v['spec_goods_id']
                //下单前的库存  $v['goods']['goods_number']  冻结库存  $v[goods]['frozen_number']
                if ($v['spec_goods_id']) {
                    //冻结SKU库存
                    $spec_goods[] = [
                        'id' => $v['spec_goods_id'],
                        'store_count' => $v['goods']['goods_number'] - $v['number'],
                        'store_frozen' => $v['goods']['frozen_number'] + $v['number']
                    ];
                } else {
                    //冻结商品库存
                    $goods[] = [
                        'id' => $v['goods_id'],
                        'goods_number' => $v['goods']['goods_number'] - $v['number'],
                        'frozen_number' => $v['goods']['frozen_number'] + $v['number'],
                    ];
                }
                //批量修改库存
                $goods_model = new \app\common\model\Goods();
                $goods_model->saveAll($goods);
                $spec_goods_model = new \app\common\model\SpecGoods();
                $spec_goods_model->saveAll($spec_goods);
            }
            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            //回滚事务
            \think\Db::rollback();
            $msg = $e->getMessage();
            $this->error($msg);
        }
        //跳转到pay方法
        $this->redirect('home/order/pay?id=' . $order['id']);
    }

    //选择支付方式
    public function pay($id)
    {
        //查询订单信息
        $order = \app\common\model\Order::find($id);
        //支付方式
        $pay_type = config('pay_type');
        //二维码图片中的支付连接（本地项目自定义链接，传递订单id参数）
        //$url=url('/home/order/qrpay',['id'=>$order->order_sn],true,true);
        //用于测试的线上域名  www.pyg.tbyue.com
        $url = url('/home/order/qrpay', ['id' => $order->order_sn, 'debug' => true], true, "http://pyg.tbyue.com");
        //生成支付二维码
        $qrCode = new \Endroid\QrCode\QrCode($url);
        //二维码图片保存路径（请先将对应目录结构创建出来，需要有写权限）
        $qr_path = '/uploads/qrcode/' . uniqid(mt_rand(100000, 999999), true) . '.png';
        //将二维码图片信息保存到文件中
        $qrCode->writeFile('.' . $qr_path);
        $this->assign('qr_path', $qr_path);
        return view('pay', ['order' => $order, 'pay_type' => $pay_type]);
    }

    //去支付
    public function topay()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require|integer|>:0',
            'pay_type|支付方式' => 'require'
        ]);
        if ($validate !== true) {
            $this->error($validate);
        }
        //查询订单信息
        $user_id = session('user_info.id');
        $order = \app\common\model\Order::where('id', $params['id'])->where('user_id', $user_id)->where('order_status', 0)->find();
        if (!$order) {
            $this->error('订单数据异常');
        }
        //记录支付方式
        $pay_type = config('pay_type');
        $pay_name = $pay_type[$params['pay_type']]['pay_name'];
        //修改到订单表
        $order->pay_code = $params['pay_type'];
        $order->pay_name = $pay_name;
        $order->save();
        //去支付
        switch ($params['pay_type']) {
            case 'wechat':
                //微信支付
                echo '微信功能测试中，暂不支持';
                break;
            case 'unionpay':
                //银联支付
                echo '银联支付功能测试中，暂不支持';
                break;
            case 'alipay':
                //支付宝支付
            default:
                $html = "<form id='alipayment' action='/plugins/alipay/pagepay/pagepay.php' method='post' style='display:none'>
                <input id='WIDout_trade_no' type='hidden' name='WIDout_trade_no' value='{$order->order_sn}'>
                <input id='WIDsubject' type='hidden' name='WIDsubject' value='品优购商城订单'>
                <input id='WIDtotal_amount' type='hidden' name='WIDtotal_amount' value='{$order->order_amount}'>
                <input id='WIDbody' type='hidden' name='WIDbody' value='优购商品，值得拥有'>
                </form><script>document.getElementById('alipayment').submit()</script>";
                echo $html;
                break;
        }
    }

    //同步通知，页面跳转
    public function callback()
    {
        //接收参数
        $params = input();
        //验证签名  参考 plugins/alipay/retutn_url.php
        require_once './plugins/alipay/config.php';
        require_once './plugins/alipay/pagepay/service/AlipayTradeService.php';
        $alipayService = new \AlipayTradeService($config);
        $result = $alipayService->check($params);
        if ($result) {
            //验签成功
            //展示页面
            return view('paysuccess', ['pay_name' => '支付宝', 'total_amount' => $params['total_amount']]);
        } else {
            //验签失败
            //展示页面
            return view('payfail', ['msg' => '参数异常，请重新支付或联系客服']);
        }
    }

    //异步通知  修改订单状态
    public function notify()
    {
        //接收参数
        $params = input();
        //验证签名  参考plugins/alipay/return_url.php
        require_once './plugins/alipay/config.php';
        require_once './plugins/alipay/pagepay/service/AlipayTradeService.php';
        $alipayService = new \AlipayTradeService($config);
        //记录日志
        trace('order_notify:异步通知开始；参数：' . json_encode($params, JSON_UNESCAPED_UNICODE), 'debug');
        $result = $alipayService->check($params);
        if ($result) {
            //验签成功
            $trade_status = $params['trade_status'];
            if ($trade_status == 'TRADE_FINISHED') {
                echo 'success';
                die;
            }
            if ($trade_status == 'TRADE_SUCCESS') {
                //查询订单信息
                $out_trade_no = $params['out_trade_no'];
                $order = \app\common\model\Order::where('order_sn', $out_trade_no)->find();
                if (!$order) {
                    trace('order_notify:订单不存在；订单编号：' . $out_trade_no, 'error');
                    echo 'fail';
                    die;
                }
                if ($order['order_amount'] != $params['total_amount']) {
                    trace('order_notify:订单支付金额不正确;订单编号：' . $out_trade_no . ';应付金额：' . $order['order_amount'] . ';实际支付金额：' . $params['total_amount'], 'error');
                    echo 'fail';
                    die;
                }
                //修改订单状态
                if ($order['order_status'] != 0) {
                    trace('order_notify:订单状态不是待付款；订单编号：' . $out_trade_no . ';订单状态：' . $order['order_status'], error);
                    echo 'fail';
                    die;
                }
                $order->order_status = 1;   //订单状态0：待付款；  1：已付款，待发货
                $order->save();
                //记录支付日志（记录到数据表paylog）
                $pay_log = [
                    'order_sn' => $order['order_sn'],
                    'json' => json_encode($params, JSON_UNESCAPED_UNICODE)
                ];
                \app\common\model\PayLog::create($pay_log, true);
                //扣减冻结库存
                $order_goods = \app\common\model\OrderGoods::with('goods,spec_goods')->where('order_id', $order['id'])->select();
                $goods = [];
                $spec_goods = [];
                foreach ($order_goods as $v) {
                    if ($v['spec_goods']) {
                        //修改SKU表冻结库存
                        $spec_goods[] = [
                            'id' => $v['spec_goods_id'],
                            'store_frozen' => $v['spec_goods']['store_frozen'] - $v['number']
                        ];
                    } else {
                        //修改商品表的冻结库存
                        $goods[] = [
                            'id' => $v['goods_id'],
                            'frozen_number' => $v['goods']['frozen_number'] - $v['number']
                        ];
                    }
                }
                //批量修改
                $goods_model = new \app\common\model\Goods();
                $goods_model->saveAll($goods);
                $spec_goods_model = new \app\common\model\SpecGoods();
                $spec_goods_model->saveAll($spec_goods);
                echo 'success';
                die;
            }
        } else {
            //验签失败
            echo 'fail';
            die;
        }
    }

    //扫码支付
    public function qrpay()
    {
        $agent = request()->server('HTTP_USER_AGENT');
        //判断扫码支付方式
        if (strops($agent, 'MicroMessanger') != false) {
            //微信支付
            $pay_code = 'wx_pub_qr';
        } else if (strops($agent, 'AlipayClient') !== false) {
            //支付宝扫码
            $pay_code = 'alipay_qr';
        } else {
            //默认为支付宝扫码支付
            $pay_code = 'alipay_qr';
        }
        //接收订单id参数
        $order_sn = input('id');
        //创建支付请求
        $this->pingpp($order_sn, $pay_code);
    }

    //发起ping++支付请求
    public function pingpp($order_sn, $pay_code)
    {
        //查询订单信息
        $order = \app\common\model\Order::where('order_sn', $order_sn)->find();
        //ping++聚合支付
        \Pingpp\Pingpp::setApiKey(config('pingpp.api_key'));    //设置API Key
        \Pingpp\Pingpp::setPrivateKeyPath(config('pingpp.private_key_path'));    //设置私钥
        \Pingpp\Pingpp::setAppId(config('pingpp.app_id'));    //设置APP ID
        $params = [
            'order_no' => $order['order_sn'],
            'app' => ['id' => config['pingpp.app_id']],
            'channel' => $pay_code,
            'amount' => $order['order_amount'] * 100,
            'client_ip' => '127.0.0.1',
            'currency' => 'cny',
            'subject' => 'Your Subject',
            'body' => 'Your Body',
            'extra' => [],
        ];
        if ($pay_code == 'wx_pub_qr') {
            $params['extra']['product_id'] = $order['id'];
        }
        //创建Charge对象
        $ch = \Pingpp\Charge::create($params);
        //跳转到对应的第三方支付链接
        $this->redirect($ch->credential->$pay_code);
        die;
    }

    //查询订单状态
    public function status()
    {
        //接收订单编号
        $order_sn = input('order_sn');
        //查询订单状态
        //$order_status=\app\common\model\Order::where('order_sn',$order_sn)->value('order_status');
        //return json(['code'=>200,'msg'=>'success','data'=>$order_status]);
        //通过线上测试
        $res = curl_request("http://pyg.tbyue.com/home/order/status/order_sn/{$order_sn}");
        echo $res;
        die;
    }
    //支付结果页面显示
    public function payresult(){
        $order_sn=input('order_sn');
        $order=\app\common\model\Order::where('order_sn',$order_sn)->find();
        if(empty($order)){
            return view('payfail',['msg'=>'订单编号错误']);
        }else{
            return view('paysuccess',['pay_name'=>$order->pay_name,'total_amount'=>$order['total_amount']]);
        }
    }
}
