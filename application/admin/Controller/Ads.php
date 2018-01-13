<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/17
 * Time: 11:18
 */
namespace app\admin\Controller;
use think\Controller;
use think\Request;

class Ads extends Base{

    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 广告列表
     */

   public function ads(){
    $ads=db('ads')->alias('a')->join('__ADS_CAT__ b ','b.id= a.p_id')->field('a.*,b.id as bid ,b.cat_ads')->paginate(20);
    $page= $ads->render();
       $ads_cat =db('ads_cat')->select();
       $this->assign('cat_ads',$ads_cat);
    $this->assign('page',$page);
    $this->assign('ads',$ads);
    return $this->fetch();
   }
    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 添加广告
     */
   public function add_ads(){

       $ads_cat =db('ads_cat')->select();
       $this->assign('cat_ads',$ads_cat);
      $but= input('do_submit');
      if(!empty($but)){
          $title =trim(input('title'));
          if($title==""){
              $this->error('请上传标题！',U('admin/ads/add_ads'),2);
          }
          $is_user = db('ads')->where('title='."'$title'")->find();
          if($is_user==true){
              $this->error('当前用户名已经存在',U('admin/ads/add_ads'),2);
          }
          $file = Request::instance()->file('ads_img');
          // 移动到框架应用根目录/public/uploads/ 目录下
          if(!empty($file)){
              $info = $file->validate(['size' =>20480000, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
              if ($info) {
                  $img = $info->getSaveName();
              } else {
                  // 上传失败获取错误信息
                  $this->error($file->getError(), U('admin/ads/add_ads'), 2);
              }
          }
        $time = input('addtime');
        $data=array(

            'title'=>trim(input('title')),
            'p_id'=>trim(input('p_id')),
            'code_lilk'=>input('code_lilk'),
            'is_show'=>input('is_show'),
            'description'=>input('description'),
            'ads_img'=>isset($img) ? $img : input('ads_img'),
            'addtime'=>!empty($time) ? $time !="" :date('Y-m-d H:i:s',time())
        );
           $res= db('ads')->insert($data);
            if($res==true){
                $this->success('添加成功',U('admin/ads/ads'),2);
            }
      }
   return   $this->fetch();
   }
    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 删除广告
     */
   public function del(){
       $id = input('id');
       $res = db('ads')->where('id=' . $id)->delete();
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
     * @info 批量删除广告
     */
    public function delAll()
    {
        $id = $_REQUEST['id'];
        if (is_array($id)) {
            $where = 'id in(' . implode(',', $id) . ')';
        } else {
            $where = 'id=' . $id;
        }
        $res = db('ads')->where($where)->delete();

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
     * @info 编辑广告
     */
    public function edit_ads(){
        $id=input('edit_id');
        $ads=db('ads')->alias('a')->join('__ADS_CAT__ b ','b.id= a.p_id')->where('a.id='.$id)->find();
        $this->assign('ads',$ads);
        $this->assign('id',$id);
        $ads_cat =db('ads_cat')->select();
        $this->assign('cat_ads',$ads_cat);
        $but= input('do_submit');
        if(!empty($but)){
            $id=input('id');
            $title =input('title');
            if(empty($title)){
                $is_user = db('ads')->where('title='."'$title'")->find();
                if($is_user==true){
                    $this->error('当前用户名已经存在',U('admin/ads/edit_ads'),2);
                }
            }
            $file = Request::instance()->file('ads_img');
            if(!empty($file)){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size' =>2048000, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $img = $info->getSaveName();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError());
                }
            }
            $time = input('addtime');

            $data=array(
                'title'=>trim(input('title')),
                'p_id'=>trim(input('cat_ads')),
                'code_lilk'=>input('code_lilk'),
                'is_show'=>input('is_show'),
                'description'=>input('description'),
                'ads_img'=>!empty($img) ? $img !="" : input('ads_img_log'),
                'addtime'=>!empty($time) && $time>0  ? $time :date('Y-m-d H:i:s',time())

            );

            $res= db('ads')->where('id='.$id)->update($data);


            if($res==true){
                $this->success('修改成功',U('admin/ads/ads'),2);
            }else{
                $this->error('修改失败');
            }
        }
        return   $this->fetch();
    }

    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 移动分类
     */

    public function upCat(){
        $id = $_REQUEST['id'];

        if (is_array($id)) {
            $where = 'id in(' . implode(',', $id) . ')';
        } else {
            $where = 'id=' . $id;
        }
        $data=array(
            'p_id'=>input('cat_id')
        );



        $res = db('ads')->where($where)->update($data);

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



    /**~*******************************************************************************
     * ~                 获取广告分类
     **********************************************************************************/

    public function ads_cat(){


        $res =db('ads_cat')->paginate(10);
        $page = $res->render();

        $this->assign('page',$page);
        $this->assign('ads_cat',$res);

        return $this->fetch();
    }

    public function add_ads_cat(){

        $cat_ads= input('cat_ads');
        $name = isset($cat_ads) ? input('cat_ads'):false;
        $bat= input('do_submit');
          if(!empty($bat)){
          $data=array(
             'cat_ads'=>$name,
              'addtime'=>time()
          );
          $ads=db('ads_cat')->insert($data);
          if($ads){
              $this->success('添加成功',U('admin/ads/ads_cat'),2);
          }
          }
        return $this->fetch();
    }


    public function edit_ads_cat(){


        $edit_id= input('edit_id');

        $ads=db('ads_cat')->where('id='.$edit_id)->find();
        $this->assign('ads',$ads);
        $do_submit= input('do_submit');


        if(empty($edit_id)){
            $this->error('非法提交数据',U('admin/ads/ads_cat'),2);
        }
        if($do_submit){
            $cat_id= input('cat_id');
            $name= input('cat_ads');

            $data=array(
                'cat_ads'=>$name
            );
            $ads=db('ads_cat')->where('id',$cat_id)->update($data);
            if($ads && $ads !="" ){
                $this->success('更新成功',U('admin/ads/ads_cat'),2);
            }

        }

        return $this->fetch();
    }

    public function del_ads_cat(){

        $id = input('cat_id');


        $res = db('ads_cat'); $res->where('id='.$id)->delete();

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

        return $this->fetch();
    }


}