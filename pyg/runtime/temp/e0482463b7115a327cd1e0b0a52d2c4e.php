<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:66:"D:\server\pyg\pyg\public/../application/home\view\center\safe.html";i:1576324610;s:51:"D:\server\pyg\pyg\application\home\view\layout.html";i:1576202190;s:56:"D:\server\pyg\pyg\application\home\view\member_left.html";i:1576310670;}*/ ?>
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
                        <li class="f-item"><a href="home.html" target="_blank">我的品优购</a></li>
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
                <div class="body userSafe">
                    <ul class="sui-nav nav-tabs nav-large nav-primary ">
                        <li class="active"><a href="#one" data-toggle="tab">密码设置</a></li>
                        <li><a href="#two" data-toggle="tab">绑定手机</a></li>
                    </ul>
                    <div class="tab-content ">
                        <div id="one" class="tab-pane active">
                            <form class="sui-form form-horizontal sui-validate" id="jsForm"
                                  action="<?php echo url('home/center/editPassword'); ?>" method="post">
                                <div class="control-group">
                                    <label for="inputusername" class="control-label">用户名：</label>
                                    <div class="controls">
                                        <input id="pwdid" class="fn-tinput" data-rule-remote="http://www.baidu.com"
                                               type="text" name="OldPassword" value="<?php echo $username; ?>" disabled
                                               required data-msg-required="请输入昵称" minlength="6"
                                               data-msg-minlength="至少输入6个字符"
                                        />

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="inputPassword" class="control-label">输入旧密码：</label>
                                    <div class="controls">
                                        <input class="fn-tinput" id="password1" type="password" name="old_password"
                                               value="" placeholder="旧密码" required >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="inputPassword" class="control-label">输入新密码：</label>
                                    <div class="controls">
                                        <input class="fn-tinput" type="password" name="new_password" value=""
                                               placeholder="新密码" required id="password" data-rule-equalto2="#password1" password="password">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="inputRepassword" class="control-label">再次输入新密码：</label>
                                    <div class="controls">
                                        <input class="fn-tinput" type="password" name="confirm_password" value=""
                                               placeholder="确认新密码" required equalTo="#password" password="password">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"></label>
                                    <div class="controls">
                                        <button type="submit" class="sui-btn btn-primary">提交按钮</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="two" class="tab-pane">
                            <!--步骤条-->
                            <div class="sui-steps steps-auto">
                                <div class="wrap">
                                    <div class="finished" id="1">
                                        <label><span class="round"><i class="sui-icon icon-pc-right"></i></span><span>第一步 验证身份</span></label><i
                                            class="triangle-right-bg"></i><i class="triangle-right"></i>
                                    </div>
                                </div>
                                <div class="wrap">
                                    <div class="todo" id="2">
                                        <label><span class="round">2</span><span>第二步 绑定新手机号</span></label><i
                                            class="triangle-right-bg"></i><i class="triangle-right"></i>
                                    </div>
                                </div>
                                <div class="wrap" >
                                    <div class="todo" id="3">
                                        <label><span class="round">3</span><span>第三步 完成</span></label>
                                    </div>
                                </div>
                            </div>

                            <!--表单-->

                            <form class="sui-form form-horizontal sui-validate" data-toggle='validate' id="bind-form">

                                <div class="control-group">
                                    <label for="inputPassword" class="control-label">验证方式：</label>
                                    <div class="controls fixed">手机验证（"<?php echo $phone; ?>"）</div>
                                </div>
                                <div class="control-group">
                                    <label for="inputcode" class="control-label">验证码：</label>
                                    <div class="controls">
                                        <input name="inputcode" value="" type="text" id="inputcode">
                                    </div>
                                    <div class="controls">
                                        <img src="<?php echo captcha_src(); ?>" onclick="this.src='<?php echo captcha_src(); ?>'+'?'+Math.random()">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="inputRepassword" class="control-label">短信验证码：</label>
                                    <div class="controls">
                                        <input name="msgcode" value="" type="text" id="msgcode">
                                    </div>
                                    <div class="controls">
                                        <button type="button" class="sui-btn btn-info" id="sendcode">发送</button>
                                    </div>
                                </div>
                                <div class="control-group next-btn">
                                    <a class="sui-btn btn-primary" href="javascript:;" id="next1">下一步</a>
                                </div>
                            </form>

                            <form class="sui-form form-horizontal sui-validate" data-toggle='validate' id="bind-form1" style="display:none">
                                <div class="control-group">
                                    <label for="inputPhone" class="control-label">手机号码：</label>
                                    <div class="controls">
                                        <input name="inputphone" value="" type="text" id="inputphone">
                                    </div>
                                    <div class="controls">
                                        <button type="button" class="sui-btn btn-info" id="sendcode2">发送验证码</button>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="inputcode" class="control-label">短信验证码：</label>
                                    <div class="controls">
                                        <input name="msgcode" value="" type="text" id="msgcode2">
                                    </div>
                                </div>
                                <div class="control-group next-btn">
                                    <a class="sui-btn btn-primary" href="javascript:;" id="next2">下一步</a>
                                </div>
                            </form>
                            <div align="center" id="bind-form2" style="font-size:30px;display:none">您的账号安全了</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/static/home/js/plugins/jquery.validate/jquery.validate.js"></script>
<script>
    $(function () {
        $("#sendcode").click(function(){
            $.ajax({
                'url':"<?php echo url('home/center/sendcode'); ?>",
                'type':'post',
                'data':'phone='+"<?php echo $phone; ?>",
                'dataType':'json',
                'success':function(res){
                    console.log(res.data);
                    return;
                }
            });
        });
        $("#next1").click(function(){
            var data={'captcha':$("#inputcode").val(),'code':$("#msgcode").val(),'phone':"<?php echo $phone; ?>"};
            $.ajax({
                'url':"<?php echo url('home/center/phonecheck'); ?>",
                'type':'post',
                'data':data,
                'dataType':'json',
                'success':function(res){
                    if(res.code==200){
                        $("#bind-form").hide();
                        $("#bind-form1").show();
                        $("#1").attr('class','todo');
                        $("#2").attr('class','finished');
                    }
                }
            });
        });
        $("#sendcode2").click(function(){
            $.ajax({
                'url':"<?php echo url('home/center/sendcode'); ?>",
                'type':'post',
                'data':'phone='+$("#inputphone").val(),
                'dataType':'json',
                'success':function(res){
                    console.log(res.data);
                    return;
                }
            });
        });
        $("#next2").click(function(){
            var data={'phone':$("#inputphone").val(),'code':$("#msgcode2").val()};
            $.ajax({
                'url':"<?php echo url('home/center/phonechange'); ?>",
                'type':'post',
                'data':data,
                'dataType':'json',
                'success':function(res){
                    if(res.code==200){
                        $("#bind-form1").hide();
                        $("#bind-form2").show();
                        $("#2").attr('class','todo');
                        $("#3").attr('class','finished');
                    }
                }
            });
        });
        //jquery.validate
        $("#jsForm").validate({
            submitHandler: function () {
                //验证通过后 的js代码写在这里
            }
        })
    })

    //下面是一些常用的验证规则扩展

    /*-------------验证插件配置 懒人建站http://www.51xuediannao.com/-------------*/

    //配置错误提示的节点，默认为label，这里配置成 span （errorElement:'span'）
    $.validator.setDefaults({
        errorElement: 'span'
    });

    //配置通用的默认提示语
    $.extend($.validator.messages, {
        required: '必填',
        equalTo: "请再次输入相同的值"
    });

    /*-------------扩展验证规则使用-------------*/
    //密码
    jQuery.validator.addMethod("password", function (value, element) {
        var password = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/;
        return this.optional(element) || (password.test(value));
    }, "密码格式不对");
    //邮箱
    jQuery.validator.addMethod("mail", function (value, element) {
        var mail = /^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$/;
        return this.optional(element) || (mail.test(value));
    }, "邮箱格式不对");

    //电话验证规则
    jQuery.validator.addMethod("phone", function (value, element) {
        var phone = /^0\d{2,3}-\d{7,8}$/;
        return this.optional(element) || (phone.test(value));
    }, "电话格式如：0371-68787027");

    //区号验证规则
    jQuery.validator.addMethod("ac", function (value, element) {
        var ac = /^0\d{2,3}$/;
        return this.optional(element) || (ac.test(value));
    }, "区号如：010或0371");

    //无区号电话验证规则
    jQuery.validator.addMethod("noactel", function (value, element) {
        var noactel = /^\d{7,8}$/;
        return this.optional(element) || (noactel.test(value));
    }, "电话格式如：68787027");

    //手机验证规则
    jQuery.validator.addMethod("mobile", function (value, element) {
        var mobile = /^1[3|4|5|7|8]\d{9}$/;
        return this.optional(element) || (mobile.test(value));
    }, "手机格式不对");

    //邮箱或手机验证规则
    jQuery.validator.addMethod("mm", function (value, element) {
        var mm = /^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$|^1[3|4|5|7|8]\d{9}$/;
        return this.optional(element) || (mm.test(value));
    }, "格式不对");

    //电话或手机验证规则
    jQuery.validator.addMethod("tm", function (value, element) {
        var tm = /(^1[3|4|5|7|8]\d{9}$)|(^\d{3,4}-\d{7,8}$)|(^\d{7,8}$)|(^\d{3,4}-\d{7,8}-\d{1,4}$)|(^\d{7,8}-\d{1,4}$)/;
        return this.optional(element) || (tm.test(value));
    }, "格式不对");

    //年龄
    jQuery.validator.addMethod("age", function (value, element) {
        var age = /^(?:[1-9][0-9]?|1[01][0-9]|120)$/;
        return this.optional(element) || (age.test(value));
    }, "不能超过120岁");
    ///// 20-60   /^([2-5]\d)|60$/

    //传真
    jQuery.validator.addMethod("fax", function (value, element) {
        var fax = /^(\d{3,4})?[-]?\d{7,8}$/;
        return this.optional(element) || (fax.test(value));
    }, "传真格式如：0371-68787027");

    //验证当前值和目标val的值相等 相等返回为 false
    jQuery.validator.addMethod("equalTo2", function (value, element) {
        var returnVal = true;
        var id = $(element).attr("data-rule-equalto2");
        var targetVal = $(id).val();
        if (value === targetVal) {
            returnVal = false;
        }
        return returnVal;
    }, "不能和原始密码相同");

    //大于指定数
    jQuery.validator.addMethod("gt", function (value, element) {
        var returnVal = false;
        var gt = $(element).data("gt");
        if (value > gt && value != "") {
            returnVal = true;
        }
        return returnVal;
    }, "不能小于0 或空");

    //汉字
    jQuery.validator.addMethod("chinese", function (value, element) {
        var chinese = /^[\u4E00-\u9FFF]+$/;
        return this.optional(element) || (chinese.test(value));
    }, "格式不对");

    //指定数字的整数倍
    jQuery.validator.addMethod("times", function (value, element) {
        var returnVal = true;
        var base = $(element).attr('data-rule-times');
        if (value % base != 0) {
            returnVal = false;
        }
        return returnVal;
    }, "必须是发布赏金的整数倍");

    //身份证
    jQuery.validator.addMethod("idCard", function (value, element) {
        var isIDCard1 = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;//(15位)
        var isIDCard2 = /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;//(18位)

        return this.optional(element) || (isIDCard1.test(value)) || (isIDCard2.test(value));
    }, "格式不对");
</script>
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