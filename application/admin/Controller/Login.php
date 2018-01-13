<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 10:37
 */
namespace app\admin\Controller;
use think\Controller;
use think\Session;

class Login extends Controller{
    /**
     * @return mixed
     * @param username ,password
     * @author MR:Daly
     * @time 2017/11/16
     */

    public function login()
    {
         $admin_id=Session::get('admin');
        if(!empty($admin_id)){
            $this->success('当前用户已经登录', U('admin/index/index'), 2);
        }
        $but = input('do_submit');
        if (!empty($but)) {
            $username = input('username');
            $password = md5(input('password'));
            $res = db('admin')->where('username=' . "'$username'" . ' and  password=' . "'$password'")->find();
            if ($res) {
                    Session::set('admin', $res['id']);
                    Session::set('username', $res['username']);
                    Session::set('role', $res['role_id']);
                    Session::set('img', $res['user_img']);
                    $this->success('登录成功', U('admin/index/index'), 2);
                } else {
                    $this->error('登录失败!', U('admin/login/login'), 2);
                }
            }
            return $this->fetch();
        }

    /**
     * @info  退出当前用户
     * @param username ,password
     * @author MR:Daly
     * @time 2017/11/16
     */

    public function logout(){
        Session::clear();

        $this->error('退出成功!',U('admin/login/login'),2);
    }


    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 修改管理员密码
     */

    public function modifyPWS(){

         $sub= input('do_submit');
        if(!empty($sub)){
            $where=array();
            $username=input('username');
            $email_code=Session::get('validate_code');
            $code=input('code');
            $email_user=input('email_user');
            $password=input('password');
            $password2=input('password2');
            $where['username']=isset($username) ? input('username') :false;
            $where['user_email']=isset($email_user) ? input('email_user') :false;
            $is_user=db('admin')->where($where)->find();
            if(!$is_user){
                $this->error('账号和邮箱地址错误');
            }
            if($password !=$password2){
              $this->error('二次密码不一致！');
            }
            if($code !=$email_code['code']){
                $this->error('你输入的验证码有误！');
            }
            if(time() >=$email_code['time']){
                Session::clear('validate_code');
                $this->error('验证码时间过期！');
            }
            $result=db('admin')->where($where)->update(array('password'=>md5($password)));
            if(!empty($result)){
                $this->success('修改成功',U('admin/login/login'),2);
            }else{
                $this->error('修改失败');
            }
        }
        return $this->fetch();

    }



}
