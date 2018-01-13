<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/12/2
 * Time: 16:48
 */
namespace app\admin\controller;
 use think\Controller;
 use think\db;

class Api extends Controller{


    /**
     * 前端发送短信方法: APP/WAP/PC 共用发送方法
     */
    public function send_validate_code(){

        $sender =I('send');
        $res = $this->send_email_code($sender);
        ajaxReturn($res);
    }

    public function send_email_code($sender){
        $sms_time_out = 180;
        $sms_time_out = $sms_time_out ? $sms_time_out : 180;
        //获取上一次的发送时间
        $send = session('validate_code');
        if(!empty($send) && $send['time'] > time() && $send['sender'] == $sender){
            //在有效期范围内 相同号码不再发送
            $res = array('status'=>-1,'msg'=>'规定时间内,不要重复发送验证码');
            return $res;
        }
        $code =  mt_rand(1000,9999);
        //检查是否邮箱格式
        if(!check_email($sender)){
            $res = array('status'=>-1,'msg'=>'邮箱码格式有误');
            return $res;
        }
        $send = send_email($sender,'验证码','您好，你的验证码是：'.$code);
        if($send['status'] == 1){
            $info['code'] = $code;
            $info['sender'] = $sender;
            $info['is_check'] = 0;
            $info['time'] = time() + $sms_time_out; //有效验证时间
            session('validate_code',$info);
            $res = array('status'=>1,'msg'=>'验证码已发送，请注意查收');
        }else{
            $res = $send;
        }
        return $res;
    }



}
