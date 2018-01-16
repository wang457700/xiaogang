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
        $list=db('messages')->alias('a')->join('__USER__ b','a.user_id=b.user_id','left')->distinct('b.six')->field('a.*,b.six')->select();
        $data=$this->commentReply($list);
        $count=count($data);
        $Page= new page($count,3);
        $list=array_slice($data,$Page->firstRow,$Page->listRows,true);
        $show_page=$Page->show();
        $this->assign('page', $show_page);
        $this->assign('list', $list);
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
