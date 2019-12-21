<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class BaseApi extends Controller
{
    //无需进行登录检测的请求
    protected $no_login=['login/login','login/verify'];
    //初始化方法
    public function _initialize()
    {
        parent::_initialize();
        //允许的源域名
        header("Access-Control-Allow-Origin: http://localhost:8080");
        //允许的请求头信息
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        //允许的请求类型
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        //允许携带证书式访问（携带cookie）
        header('Access-Control-Allow-Credentials:true');
        //登录检测
        $this->checkLogin();
    }
    public function checkLogin(){
        try{
            //特殊页面不需检测
            $path=strtolower(request()->controller().'/'.request()->action());
            if(in_array($path,$this->no_login)){
//                无需检测
                return;
            }
//            进行登录检测，解析token
            $user_id=\tools\jwt\Token::getUserId();
            if(empty($user_id)){
                //未登录或token无效
                $this->fail('未登录或token无效');
            }
            //将登录的用户id，记录到当前请求对象中，后续需要使用，可直接从中获取
            request()->get(['user_id'=>$user_id]);
            request()->post(['user_id'=>$user_id]);
            //权限检测
            $auth_check=\app\adminapi\logic\AuthLogic::check();
            if(!$auth_check){
                $this->fail('无访问权限',402);
            }
        }catch(\Exception $e){
//            $msg=$e->getMessage();    //获取异常中的提示信息
//            $file=$e->getFile();    //获取报错的文件名称
//            $line=$e->getLine();    //获取报错的行号
//            $this->fail('错误信息：'.$msg.';文件名：'.$file.';行号：'.$line);
            $this->fail('token无效',400);
        }
    }
    /**
     * 通用响应
     * @param int $code 错误码
     * @param string $msg 错误描述
     * @param array $data 返回数据
    */
    public function response($code=200,$msg='success',$data=[])
    {
        $res=[
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ];
        echo json_encode($res,JSON_UNESCAPED_UNICODE);die;
    }
    /**
     * 成功时响应
     * @param array $data 返回数据
     * @param int $code 错误码
     * @param string $msg 错误描述
     */
    public function ok($data=[],$code=200,$msg='success'){
        return $this->response($code,$msg,$data);
    }
    /**
     * 失败时响应
     * @param string $msg 错误描述
     * @param int $code 错误码
     */
    public function fail($msg='fail',$code=500){
        return $this->response($code,$msg);
    }
}
