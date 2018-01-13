<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/12/29
 * Time: 11:01
 */
namespace app\home\controller;

use think\Db;
use think\helper\hash\Md5;
use think\Session;

class Login extends Base{

    /**
     * @return mixed
     */

    public function login(){
        $to=input('to');

        if($to=='log'){
           $where=array(
            'nickname'=>input('username'),
            'password'=>Md5(input('password'))
           );
            $user=db('user')->where($where)->find();
            if($user){
                Session::set('nickname',input('username'));
                Session::set('user_id',$user['user_id']);
                $this->success('登录成功!',U('Message/message'),2);
            }else{
                $this->error('登录失败！检测账号密码');
            }
        }
      return  $this->fetch();
    }

    /**
     * 注册页面！
     */

    public function reg(){
        $to=input('to');
        if($to=='reg'){
            $data=array(
                'nickname'=>input('user'),
                'password'=>Md5(input('password')),
                'six'=>Md5(input('six')),
                'addtime'=>date('Y-m-d H:i:s',time()),
            );
             $addUser=db('user')->insert($data);
             if($addUser){
                 $this->success('注册成功！请登录',U('login/login'),2);
             }
        }
    }

    /***
     * 退出登录！
     */
    public function logout(){

        Session::clear();

        $this->error('退出成功!',U('index/index'),2);
    }


}