<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/10
 * Time: 16:20
 */

namespace app\home\Controller;
use think\Controller;

class About extends Base
{


    public function index(){


    }

    public function about()
    {
        $that_year =GetOne('article','cat_id',14);
        $ads_article =GetOne('article','cat_id',15);
        $wonderful= db('article')->where('cat_id',17)->select();
        $selfPic= db('ads')->where('p_id',12)->select();
        $this->assign('selfPic', $selfPic);
        $KingCarry= db('article')->alias('a')->join('__ARTICLE_CAT__ b','a.cat_id=b.id')->field('a.*,b.id as bid,b.cat_name')->where('a.cat_id',22)->select();
        $this->assign('KingCarry', $KingCarry);
        $this->assign('wonderful', $wonderful);
        $this->assign('that_year', $that_year);
        $this->assign('article', $ads_article);
        return $this->fetch();


    }


}