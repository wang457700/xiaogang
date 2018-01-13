<?php
namespace app\home\controller;

use think\Controller;
use think\Db;

class Index extends  Controller{

    /**
     * @return mixed
     */

     public function Index(){

    $ads = db('ads')->order('id desc')->where('p_id =13')->select();
    $this->assign('ads',$ads);
    return $this->fetch();
    }



}

