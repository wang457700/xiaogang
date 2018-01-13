<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 15:03
 */
namespace app\admin\Controller;

use think\Controller;

Class Messages extends Base{

    /**
     * @return mixed
     * @list 留言列表
     * @author MR:Daly
     */
    public function messages(){
    $result=db('messages')->paginate(5);
    $page = $result->render();
    $this->assign('page',$page);
    $this->assign('list',$result);
    return $this->fetch();
    }
    /**
     * @return mixed
     * @list 删除留言
     * @author MR:Daly
     */
    public function del_mes()
    {
        $id = input('id');
        $res = db('messages')->where('id=' . $id)->delete();
        if ($res == true) {
            $data = array(
                'status' => 1
            );
            $this->ajaxReturn($data);
        } else {
            $data = array(
                'status' => 0
            );
            $this->ajaxReturn($data);
        };
    }

    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 批量删除管理员
     */
    public function delAll_mes()
    {
        $id = $_REQUEST['id'];
        if (is_array($id)) {
            $where = 'id in(' . implode(',', $id) . ')';
        } else {
            $where = 'id=' . $id;
        }
        $res = db('messages')->where($where)->delete();

        if ($res == true) {
            $data = array(
                'status' => 1
            );
            $this->ajaxReturn($data);

        } else {
            $data = array(
                'status' => 0
            );
            $this->ajaxReturn($data);

        };
    }

    public function  status(){
         $status = input('status');
        $cid = input('mes_id');
        $data=array(
            'is_show'=>$status
        );

       $ok=db('messages');$ok->where('id='.$cid)->update($data);

       if ($ok ==true){

           $data = array(
               'status' => 1
           );
           $this->ajaxReturn($data);
       }else{

           $data = array(
               'status' => 0
           );
           $this->ajaxReturn($data);
       }

    }
}