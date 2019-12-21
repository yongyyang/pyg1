<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:69:"D:\server\pyg\pyg\public/../application/home\view\center\address.html";i:1576389371;s:51:"D:\server\pyg\pyg\application\home\view\layout.html";i:1576371503;s:56:"D:\server\pyg\pyg\application\home\view\member_left.html";i:1576310670;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link rel="stylesheet" type="text/css" href="/static/home/css/all.css"/>
    <script type="text/javascript" src="/static/home/js/all.js"></script>
</head>
<body>
<!-- 头部栏位 -->
<!--页面顶部-->
<div id="nav-bottom">
    <!--顶部-->
    <div class="nav-top">
        <div class="top">
            <div class="py-container">
                <div class="shortcut">
                    <ul class="fl">
                        <li class="f-item">品优购欢迎您！</li>
                        <?php if((\think\Session::get('user_info')==null)): ?>
                        <li class="f-item">请<a href="<?php echo url('home/login/login'); ?>">登录</a><span><a
                                href="<?php echo url('home/login/register'); ?>">免费注册</a></span>
                        </li>
                        <?php else: ?>
                        <li class="f-item">Hi,<a href="<?php echo url('home/center/footmark'); ?>"><?php echo \think\Session::get('user_info.nickname'); ?></a>　<span><a
                                href="<?php echo url('home/login/logout'); ?>">退出</a></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <ul class="fr">
                        <li class="f-item">我的订单</li>
                        <li class="f-item space"></li>
                        <li class="f-item"><a href="<?php echo url('home/center/footmark'); ?>" target="_blank">我的品优购</a></li>
                        <li class="f-item space"></li>
                        <li class="f-item">品优购会员</li>
                        <li class="f-item space"></li>
                        <li class="f-item">企业采购</li>
                        <li class="f-item space"></li>
                        <li class="f-item">关注品优购</li>
                        <li class="f-item space"></li>
                        <li class="f-item" id="service">
                            <span>客户服务</span>
                            <ul class="service">
                                <li><a href="cooperation.html" target="_blank">合作招商</a></li>
                                <li><a href="shoplogin.html" target="_blank">商家后台</a></li>
                            </ul>
                        </li>
                        <li class="f-item space"></li>
                        <li class="f-item">网站导航</li>
                    </ul>
                </div>
            </div>
        </div>

        <!--头部-->
        <div class="header">
            <div class="py-container">
                <div class="yui3-g Logo">
                    <div class="yui3-u Left logoArea">
                        <a class="logo-bd" title="品优购" href="JD-index.html" target="_blank"></a>
                    </div>
                    <div class="yui3-u Center searchArea">
                        <div class="search">
                            <form action="<?php echo url('home/goods/index'); ?>" method="get" class="sui-form form-inline">
                                <!--searchAutoComplete-->
                                <div class="input-append">
                                    <input type="text" id="autocomplete" type="text" class="input-error input-xxlarge" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>"/>
                                    <button class="sui-btn btn-xlarge btn-danger" type="submit">搜索</button>
                                </div>
                            </form>
                        </div>
                        <div class="hotwords">
                            <ul>
                                <li class="f-item">品优购首发</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">每满99减30</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">办公用品</li>

                            </ul>
                        </div>
                    </div>
                    <div class="yui3-u Right shopArea">
                        <div class="fr shopcar">
                            <div class="show-shopcar" id="shopcar">
                                <span class="car"></span>
                                <a class="sui-btn btn-default btn-xlarge" href="<?php echo url('home/cart/index'); ?>" target="_blank">
                                    <span>我的购物车</span>
                                    <i class="shopnum">0</i>
                                </a>
                                <div class="clearfix shopcarlist" id="shopcarlist" style="display:none">
                                    <p>"啊哦，你的购物车还没有商品哦！"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="yui3-g NavList">
                    <div class="all-sorts-list">
                        <div class="yui3-u Left all-sort">
                            <h4>全部商品分类</h4>
                        </div>
                        <div class="sort">
                            <div class="all-sort-list2">
                                <?php foreach($category as $one): ?>
                                <div class="item bo">
                                    <h3><a href="javascript:;"><?php echo $one['cate_name']; ?></a></h3>
                                    <div class="item-list clearfix">
                                        <div class="subitem">
                                            <?php foreach($one['son'] as $two): ?>
                                            <dl class="fore1">
                                                <dt><a href="javascript:;"><?php echo $two['cate_name']; ?></a></dt>
                                                <dd>
                                                    <?php foreach($two['son'] as $three): ?>
                                                    <em><a href="<?php echo url('home/goods/index',['id'=>$three['id']]); ?>"><?php echo $three['cate_name']; ?></a></em>
                                                    <?php endforeach; ?>
                                                </dd>
                                            </dl>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="yui3-u Center navArea">
                        <ul class="nav">
                            <li class="f-item">服装城</li>
                            <li class="f-item">美妆馆</li>
                            <li class="f-item">品优超市</li>
                            <li class="f-item">全球购</li>
                            <li class="f-item">闪购</li>
                            <li class="f-item">团购</li>
                            <li class="f-item">有趣</li>
                            <li class="f-item"><a href="seckill-index.html" target="_blank">秒杀</a></li>
                        </ul>
                    </div>
                    <div class="yui3-u Right"></div>
                </div>

            </div>
        </div>
    </div>
</div>
<title>设置-个人信息</title>
<link rel="stylesheet" type="text/css" href="/static/home/css/pages-seckillOrder.css"/>
<!--header-->
<div id="account">
    <div class="py-container">
        <div class="yui3-g home">
            <!--左侧列表-->
            <!--个人中心-左侧列表-->
<div class="yui3-u-1-6 list">

    <div class="person-info">
        <div class="person-photo"><img src="/static/home/img/_/photo.png" alt=""></div>
        <div class="person-account">
            <span class="name">Michelle</span>
            <span class="safe">账户安全</span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="list-items">
        <dl>
            <dt><i>·</i> 订单中心</dt>
            <dd ><a href="seckillOrder.html">我的订单</a></dd>
            <dd><a href="seckillorder-pay.html">待付款</a></dd>
            <dd><a href="seckillorder-send.html">待发货</a></dd>
            <dd><a href="seckillorder-receive.html">待收货</a></dd>
            <dd><a href="seckillorder-evaluate.html">待评价</a></dd>
        </dl>
        <dl>
            <dt><i>·</i> 我的中心</dt>
            <dd><a href="<?php echo url('home/center/collect'); ?>">我的收藏</a></dd>
            <dd><a href="<?php echo url('home/center/footmark'); ?>">我的足迹</a></dd>
        </dl>
        <dl>
            <dt><i>·</i> 物流消息</dt>
        </dl>
        <dl>
            <dt><i>·</i> 设置</dt>
            <dd><a href="<?php echo url('home/center/info'); ?>">个人信息</a></dd>
            <dd><a href="<?php echo url('home/center/address'); ?>">地址管理</a></dd>
            <dd><a href="<?php echo url('home/center/save'); ?>" class="list-active">安全管理</a></dd>
        </dl>
    </div>
</div>
            <!--右侧主内容-->
            <div class="yui3-u-5-6">
                <div class="body userAddress">
                    <div class="address-title">
                        <span class="title">地址管理</span>
                        <a data-toggle="modal" data-target="#addModal" data-keyboard="false"
                           class="sui-btn  btn-info add-new">添加新地址</a>
                        <span class="clearfix"></span>
                    </div>
                    <div class="address-detail">
                        <table class="sui-table table-bordered">
                            <thead>
                            <tr>
                                <th>姓名</th>
                                <th>地址</th>
                                <th>联系电话</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $v): ?>
                            <tr address_id="<?php echo $v['id']; ?>" area="<?php echo $v['area']; ?>" address="<?php echo $v['address']; ?>">
                                <td><?php echo $v['consignee']; ?></td>
                                <td><?php echo $v['area']; ?> <?php echo $v['address']; ?></td>
                                <td><?php echo $v['phone']; ?></td>
                                <td>
                                    <button data-toggle="modal" data-target="#editModal" class="edit" data-keyboard="false" style="background:#99ffcc">编辑</button>
                                    <button style="background:#ffcc99">删除</button>
                                    <?php if(($v['is_default'])): ?>
                                    默认地址
                                    <?php else: ?>
									<button style="background:#00ffff">设为默认</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!--新增地址弹出层-->
                    <div id="addModal" tabindex="-1" role="dialog" data-hasfoot="false" class="sui-modal hide fade"
                         style="width:580px;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="sui-close">×
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title">新增地址</h4>
                                </div>
                                <div class="modal-body">
                                    <form action="" class="sui-form form-horizontal">
                                        <div class="control-group">
                                            <label class="control-label">收货人：</label>
                                            <div class="controls">
                                                <input type="text" class="input-medium">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">所在地区：</label>
                                            <div class="controls">
                                                <div data-toggle="distpicker">
                                                    <div class="form-group area">
                                                        <select class="form-control" id="province1"></select>
                                                    </div>
                                                    <div class="form-group area">
                                                        <select class="form-control" id="city1"></select>
                                                    </div>
                                                    <div class="form-group area">
                                                        <select class="form-control" id="district1"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">详细地址：</label>
                                            <div class="controls">
                                                <input type="text" class="input-large">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">联系电话：</label>
                                            <div class="controls">
                                                <input type="text" class="input-medium">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-ok="modal" class="sui-btn btn-primary btn-large">确定
                                    </button>
                                    <button type="button" data-dismiss="modal" class="sui-btn btn-default btn-large">
                                        取消
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--新增地址弹出层-->
                    <div id="editModal" tabindex="-1" role="dialog" data-hasfoot="false" class="sui-modal hide fade"
                         style="width:580px;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="sui-close">×
                                    </button>
                                    <h4 id="myModalLabel2" class="modal-title">新增地址</h4>
                                </div>
                                <div class="modal-body">
                                    <form action="" class="sui-form form-horizontal">
                                        <div class="control-group">
                                            <label class="control-label">收货人：</label>
                                            <div class="controls">
                                                <input type="text" class="input-medium">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">所在地区：</label>
                                            <div class="controls">
                                                <div id="distpicker2">
                                                    <div class="form-group area">
                                                        <select class="form-control" id="province2"></select>
                                                    </div>
                                                    <div class="form-group area">
                                                        <select class="form-control" id="city2"></select>
                                                    </div>
                                                    <div class="form-group area">
                                                        <select class="form-control" id="district2"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">详细地址：</label>
                                            <div class="controls">
                                                <input type="text" class="input-large">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">联系电话：</label>
                                            <div class="controls">
                                                <input type="text" class="input-medium">
                                            </div>
                                        </div>
                                    </form>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-ok="modal" class="sui-btn btn-primary btn-large">确定
                                    </button>
                                    <button type="button" data-dismiss="modal" class="sui-btn btn-default btn-large">
                                        取消
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/static/home/js/plugins/citypicker/distpicker.data.js"></script>
<script type="text/javascript" src="/static/home/js/plugins/citypicker/distpicker.js"></script>
<script type="text/javascript" src="/static/home/js/pages/main.js"></script>
<script>
    $(function () {
        $('.edit').click(function () {
            var id = $(this).closest('tr').attr('address_id');
            $('#editModal input[name=id]').val(id);
            var consignee = $(this).closest('tr').find('td:first').html();
            var phone = $(this).closest('tr').find('td:eq(2)').html();
            var address = $(this).closest('tr').attr('address');
            $('#editModal input[name=consignee]').val(consignee);
            $('#editModal input[name=phone]').val(phone);
            $('#editModal input[name=address]').val(address);
            var area = $(this).closest('tr').attr('area').split(' ');
            $("#distpicker2").distpicker('destroy').distpicker({
                province: area[0],
                city: area[1],
                district: area[2]
            });
        });
    });

</script>>
</body>

</html>
<!-- 底部栏位 -->
<div class="clearfix footer">
    <div class="py-container">
        <div class="footlink">
            <div class="Mod-service">
                <ul class="Mod-Service-list">
                    <li class="grid-service-item intro  intro1">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro2">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro  intro3">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro4">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro intro5">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                </ul>
            </div>
            <div class="clearfix Mod-list">
                <div class="yui3-g">
                    <div class="yui3-u-1-6">
                        <h4>购物指南</h4>
                        <ul class="unstyled">
                            <li>购物流程</li>
                            <li>会员介绍</li>
                            <li>生活旅行/团购</li>
                            <li>常见问题</li>
                            <li>购物指南</li>
                        </ul>

                    </div>
                    <div class="yui3-u-1-6">
                        <h4>配送方式</h4>
                        <ul class="unstyled">
                            <li>上门自提</li>
                            <li>211限时达</li>
                            <li>配送服务查询</li>
                            <li>配送费收取标准</li>
                            <li>海外配送</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>支付方式</h4>
                        <ul class="unstyled">
                            <li>货到付款</li>
                            <li>在线支付</li>
                            <li>分期付款</li>
                            <li>邮局汇款</li>
                            <li>公司转账</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>售后服务</h4>
                        <ul class="unstyled">
                            <li>售后政策</li>
                            <li>价格保护</li>
                            <li>退款说明</li>
                            <li>返修/退换货</li>
                            <li>取消订单</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>特色服务</h4>
                        <ul class="unstyled">
                            <li>夺宝岛</li>
                            <li>DIY装机</li>
                            <li>延保服务</li>
                            <li>品优购E卡</li>
                            <li>品优购通信</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>帮助中心</h4>
                        <img src="/static/home/img/wx_cz.jpg">
                    </div>
                </div>
            </div>
            <div class="Mod-copyright">
                <ul class="helpLink">
                    <li>关于我们<span class="space"></span></li>
                    <li>联系我们<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>商家入驻<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们</li>
                </ul>
                <p>地址：北京市昌平区建材城西路金燕龙办公楼一层 邮编：100096 电话：400-618-4000 传真：010-82935100</p>
                <p>京ICP备08001421号京公网安备110108007702</p>
            </div>
        </div>
    </div>
</div>
<!--页面底部END-->
</body>
</html>