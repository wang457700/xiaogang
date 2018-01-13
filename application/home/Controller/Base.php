<?php
namespace  app\home\Controller;
use think\Controller;
use think\Db;

class Base extends  Controller{


    public function _initialize()
    {
        header("Content-type: text/html; charset=utf-8");
        $config= db('baseconf')->find();
        $this->assign('config', $config);
    }

    public function ajaxReturn($data,$type = 'json'){
        exit(json_encode($data));
    }


}



?>
