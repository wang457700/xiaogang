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
use think\Session;
use think\paginator\driver\Bootstrap;
use think\Page;

class Message extends Base
{


    /***
     * @return mixed
     *
     */

    public function message()
    {
        error_reporting(E_ERROR | E_PARSE );
        header("Content-type: text/html; charset=utf-8");
        //$Page  = new Page( count($count),2);*/

        $p=db('messages')->paginate(1);

        $page = $p->render();

        $list=db('messages')->alias('a')->join('__USER__ b','a.user_id=b.user_id','left')->distinct('b.six')->field('a.*,b.six')->select();

        $data=$this->commentReply($list);

        $curpage = I('page') ? input('page') : 1;
        $count=count($data);
        $listRow = 10;
        $start=($page-1)*$count;
       // $showdata = array_slice($data,$start,$count);
            $data[$curpage-1];
            $showdata = array_chunk($data[$curpage-1], count($data[0]),true);
            /*  $res=db('messages')->alias('a')
             ->join('__COMMENT__ b ',' a.id=b.mes_id','left')
             ->join('__PEPLY__ c', 'b.mes_id=c.mes_id','left')
             ->field('a.*,b.id as comment_id,b.content as comment_content,b.nickname,b.user_id as comment_user_id,b.addtime as comment_addtime,c.reply_id,c.mes_id as peply_mes_id,c.nickname as peply_nickname,c.addtime as peply_addtime')
             ->order('a.addtime desc')->paginate(5);*/
              $res = Bootstrap::make($showdata, $listRow, $curpage, count($data), false, [
                'var_page' => 'page',
                'path'     => url('Message/message'),
                'query'    => [],
                'fragment' => '',
                 'type'=>'bootstrap',
                 'list_rows' => 15
            ]);
            $res->appends($_REQUEST);
            $this->assign('page', $page);



            $this->assign('list', $res);

        return $this->fetch();
    }

    /**
     * @param $res
     * @return mixed
     */

    public function     commentReply($res){

        foreach ($res as $key=>$item){
            $comment = db('comment')->where(array('mes_id'=>$item['id']))->select();
            foreach ($comment as $kk=>$yy){
                if($item['id']==$yy['mes_id']){
                    $reply = db('peply')->where(array('mes_id'=>$yy['id']))->select();
                    foreach ($reply as $k=>$y){
                        if($yy['id']==$y['mes_id']) {
                            $comment[$kk]['reply'][] = $y;
                        }
                    }
                    $res[$key]['comment'][]=$comment[$kk];

                }
            }
        }

        return $res;
    }

    /***
     * 点赞
     */
    public function dianzhan(){
        if(!Session::get('user_id')>0){
            $data=array(
                /* 'flower'=>$flower['flower'],*/
                'status'=>Session::get('user_id') ? Session::get('user_id') :0
            );
            $this->ajaxReturn($data);
        }
         $num= input('num');
        $res = db('messages');$res->where('id',$num)->setInc('dianzhan');
         if($res){
              $dianzhan=db('messages')->where('id',$num)->field('dianzhan')->find();
              $data=array(
                  'dianzhan'=>$dianzhan['dianzhan'],
                  'status'=>Session::get('user_id') ? Session::get('user_id') :0
              );

             $this->ajaxReturn($data);
         }
    }

    /**
     * 送新鲜
     */
    public function flower(){

        if(!Session::get('user_id')>0){
            $data=array(
               /* 'flower'=>$flower['flower'],*/
                'status'=>Session::get('user_id') ? Session::get('user_id') :0
            );
            $this->ajaxReturn($data);
        }

        $num= input('num');
        $res = db('messages');$res->where('id',$num)->setInc('flower');
        if($res){
            $flower=db('messages')->where('id',$num)->field('flower')->find();
            $data=array(
                'flower'=>$flower['flower'],
                'status'=>Session::get('user_id') ? Session::get('user_id') :0
            );
            $this->ajaxReturn($data);
        }

    }

    /**
     * 添加留言
     */
    public function addMessage(){

        if(!Session::get('user_id')>0){
            $this->error('请先登录!',U('login/login'),2);
        }

           $message=input('message');
        $time=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);;
       $map=array();
        $map['author']= Session::get('nickname');
        $map['addtime']= ['<=',$time];
        $res=db('messages')->where($map)->count();
        if($res>10){
            $this->error('当前用户最多只能留言10条');
        }
        if(!empty($message)){
            $data=array(
                'author'=>Session::get('nickname'),
                'user_id'=>Session::get('user_id'),
                'content'=>$message,
                'addtime'=>date('Y-m-d H:i:s',time()),
                'ip'=>request()->ip()
            );
            $res=db('messages')->insert($data);
            if($res){
         /*       $ip= request()->ip();
                Session::set('ip',$ip);
                Session::set('time',time());*/
                $this->success('留言成功');
            }
        }else{
            $this->error('留言失败');
        }
    }

    /**
     * @param uid 用户ID content 评论内容
     * @return json(array);
     * @author MR:Daly
     * @data 2017/12/30
     */
    public function comment(){
        $data=array(
            'mes_id'=>input('mes_id'),
            'user_id'=>Session::get('user_id'),
            'nickname'=>input('name'),
            'content'=>input('content'),
            'addtime'=>date('Y-m-d H:i:s',time()),
        );
        $mes=db('comment')->insert($data);
        if($mes){
            $this->ajaxReturn(1);
        }

    }


    /**
     * @param uid 用户ID content 回复内容
     * @return json(array);
     * @author MR:Daly
     * @data 2017/12/30
     */
    public function reply(){
        $data=array(
            'mes_id'=>input('mes_id'),
            'user_id'=>Session::get('user_id'),
            'nickname'=>input('name'),
            'content'=>input('content'),
            'addtime'=>date('Y-m-d H:i:s',time()),
        );
        $mes=db('peply')->insert($data);
        if($mes){
            $this->ajaxReturn(1);
        }

    }


    /**
     * @param $data
     * @param string $type
     */
    public function ajaxReturn($data,$type = 'json'){
        exit(json_encode($data));
    }
}