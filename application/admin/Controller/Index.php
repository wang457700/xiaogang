<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 10:27
 */
namespace app\admin\Controller;
use think\Controller;

use think\Db;
use think\Cache;
Class Index extends Base{

    public function index(){
        error_reporting(E_ERROR | E_PARSE );
        $menu=getMenuList();
        $this->assign('menu',$menu);
       return $this->fetch();

    }


    public function Welcome(){

        $Redis= $this->Redis();

         $Redis->set('username','wangxiaogang');

        echo $Redis->get('username'); die;


        return $this->fetch();
    }


/*    function createNoncestr( $length = 1 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ ) {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }*/

    public function index_right(){




       return $this->fetch();

    }



}

