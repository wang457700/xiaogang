<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/10
 * Time: 16:21
 */
namespace app\home\controller;
use app\home\controller\Api;
use think\Controller;

class Contact extends Base
{
     public   function  Contact(){
           $sub=input('email');
         if(!empty($sub)){
             $email =input('email');
             $title =input('title');
             $message =input('message');
             $res = $this->send_email_code($email,$title,$message);
           if($res){
               $this->success("'$res[msg]'",U('Contact/contact'),2);
           }
         }

      return  $this->fetch();
    }

    public function send_email_code($email,$title,$message){
        $sms_time_out = 180;
        $sms_time_out = $sms_time_out ? $sms_time_out : 180;
        //获取上一次的发送时间
        $send = session('validate_code');
        if(!empty($send) && $send['time'] > time() && $send['email'] == $email){
            //在有效期范围内 相同号码不再发送
            $res = array('status'=>-1,'msg'=>'规定时间内,不要重复发送验证码');
            return $res;
        }

        //检查是否邮箱格式
        if(!check_email($email)){
            $res = array('status'=>-1,'msg'=>'邮箱码格式有误');
            return $res;
        }
        $send = send_email($email,$title,$message);
        if($send['status'] == 1){
            $info['code'] = $title;
            $info['email'] = $email;
            $info['is_check'] = 0;
            $info['time'] = time() + $sms_time_out; //有效验证时间
            session('validate_code',$info);
            $res = array('status'=>1,'msg'=>'邮箱已发送，请注意查收');
        }else{
            $res = $send;
        }
        return $res;
    }



}