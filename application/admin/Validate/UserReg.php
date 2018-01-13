<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/14
 * Time: 17:05
 */
namespace app\admin\Validate;
use think\Validate;
use think\Controller;
use think\Db;
Class UserReg extends Validate{


        protected $rule = [
            'nickname'             =>  'require|max:80',
            'mobile_phone'                =>  ['regex'=>'/^1[3|4|5|8][0-9]\d{4,8}$/'],
        ];

        protected $msg = [

            'nickname.require'     =>  '姓名不能为空',
            'nickname.max'         =>  '姓名最多不能超过80个字符',
            'mobile_phone.regex'          =>  '手机号码格式错误'
        ];

        public function Clik($data){

            $validate = new Validate($this->rule, $this->msg);
            $result   = $validate->check($data);



            if($result==true){
            $User= db('user')->insert($data);
            return $User;
            }else{
            return $validate->getError();


            }


        }

}