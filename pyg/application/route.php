<?php
use think\Route;
//后台接口路径
Route::domain('adminapi',function (){
    //默认首页  adminapi.pyg.com  访问到  adminapi/index/index
    Route::get('/','adminapi/index/index');
    //获取验证码地址
    Route::get('captcha/:id',"\\think\\captcha\\CaptchaController@index");
    //显示验证码图片
    Route::get('verify','adminapi/login/verify');
    //登录
    Route::post('login','adminapi/login/login');
    //退出
    Route::get('logout','adminapi/login/logout');
    //单图片上传
    Route::post('logo','adminapi/upload/logo');
    //多图片上传
    Route::post('images','adminapi/upload/images');
    //商品分类接口
    Route::resource('categorys','adminapi/category');
    //商品品牌接口
    Route::resource('brands','adminapi/brand');
    //商品模型（类型）接口
    Route::resource('types','adminapi/type');
    //权限接口
    Route::resource('auths','adminapi/auth');
    //菜单权限
    Route::get('nav','adminapi/auth/nav');
    //角色接口
    Route::resource('roles','adminapi/role');
    //管理员接口
    Route::resource('admins','adminapi/admin');
    //商品接口
    Route::resource('goods','adminapi/goods');
});