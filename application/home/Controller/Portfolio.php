<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/10
 * Time: 16:22
 */
namespace app\home\controller;

use app\home\Controller\Base;
use think\Controller;

class Portfolio extends Base
{





    public function Portfolio()
    {
       $res = db('article_cat')->alias('a')->join('__ARTICLE__ b','a.id=b.cat_id')->where('a.parent_id',25)->field('b.id as bid,a.cat_name,b.*')->select();
        $cat_name=db('article_cat')->where('parent_id',25)->select();
        $this->assign('cat_name',$cat_name);
       $this->assign('articleList',$res);
        return $this->fetch();


    }

}