<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/29
 * Time: 14:28
 */
namespace app\home\Controller;
use think\Controller;

class Article extends Base
{

    public function article(){
          $id=input('id');
          $id = isset($id) ? $id : false;
           if($id==""){
               $this->error('查看的数据不存在！');
           }
          $article= db('article')->where('id='.$id)->find();
          $this->assign('article',$article);

       return $this->fetch();
    }
}