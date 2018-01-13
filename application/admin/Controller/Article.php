<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 13:50
 */
namespace app\admin\Controller;
use think\Validate;
use think\Controller;
use think\Db;
use think\Request;
class Article extends Base{

    public function Article(){
        $where = array();
        $keywords = trim(input('keywords'));
        $keywords && $where['title'] = array('like', '%' . $keywords . '%');
        $where['cat_name'] = array('like', '%' . $keywords . '%');
        $res = db('article')->alias('a')->join('__ARTICLE_CAT__ b ','b.id= a.cat_id')->whereOR($where)->field('a.*,b.cat_name,b.id as b_id')->paginate(5);

        $page = $res->render();
        $this->assign('page',$page);
        $this->assign('article_list',$res);
        $this->initEditor();
    return $this->fetch();
    }
     public function article_del(){
         $cat_id =input('cat_id');
         $res = db('article')->where('id='.$cat_id)->delete();
         if($res==true){
             $result=array(
                 'status'=>1,
                 'info'=>"删除成功！"
             );
             $this->ajaxReturn($result);
         }else{
             $result=array(
                 'status'=>0,
                 'info'=>"删除失败！"
             );
             $this->ajaxReturn($result);
         }
     }

    /**
     * 初始化编辑器链接
     * @param $post_id post_id
     */
    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp',array('savepath'=>'article')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp',array('savepath'=>'article')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp',array('savepath'=>'article')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage',array('savepath'=>'article')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager',array('savepath'=>'article')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp',array('savepath'=>'article')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie',array('savepath'=>'article')));
        $this->assign("URL_home", "");
    }

    public function edit_article(){
          $id=input('id');
         if(empty($id)) {
            $this->error('非法操作数据',U('admin/article/article'),2);
         }
         $list = db('article')->field('a.*,b.cat_name')->alias('a')->join('__ARTICLE_CAT__ b ','b.id= a.cat_id')->where('a.id='.$id)->find();
        $parentArticle=db('article_cat')->order('parent_id ASC')->select();
        $result=getTree($parentArticle);
         $this->assign('art_cate',$result);
         $this->assign('list',$list);
         $but= input('do_submit');
         if(!empty($but)){

             $file = Request::instance()->file('images');
             // 移动到框架应用根目录/public/uploads/ 目录下
             if(!empty($file)){
                 $info = $file->validate(['size' => 15678000, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                 if ($info) {
                     $images = $info->getSaveName();
                 } else {
                     // 上传失败获取错误信息
                     $this->error($file->getError());
                 }
             }
             $time = input('add_time');
             $data=array(
                 'title'=>input('title'),
                 'is_show'=>input('is_show'),
                 'author'=>input('author'),
                 'description'=>input('description'),
                 'content'=>input('content'),
                 'add_time'=> !empty($time) ? $time :date('Y-m-d H:i:s',time()),
                 'images'=>!empty($images) ? $images:input('images_log'),
                 'cat_id'=>input('art_cate')
             );

             $res=db('article')->where('id='.$id)->update($data);
             if($res==true){
                 $this->success('修改成功',U('admin/article/article'),2);
             }else{

                 $this->error('修改失败');
             }
         }
        $this->initEditor();
       return $this->fetch();
     }

    public function add_article(){

        $parentArticle=db('article_cat')->order('parent_id ASC')->select();
        $result=getTree($parentArticle);
        $this->assign('art_cate',$result);
        $but= input('do_submit');
       if(!empty($but)){
           $title=input('title');
           $typeTitle=input('art_cate');
           if($title==""){
              $this->error('标题不能为空',U('admin/article/add_article'));
           }
           if(empty($typeTitle)){
               $this->error('请选择分类',U('admin/article/add_article'));
           }
           $file = Request::instance()->file('images');
           // 移动到框架应用根目录/public/uploads/ 目录下
           if(!empty($file)){
               $info = $file->validate(['size' => 15678000, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
               if ($info) {
                   $images = $info->getSaveName();
               } else {
                   // 上传失败获取错误信息
                   $this->error($file->getError());
               }
           }
           $time = input('add_time');
           $data=array(
                'title'=>input('title'),
                'is_show'=>input('is_show'),
                'author'=>input('author'),
                'description'=>input('description'),
               'content'=>input('content'),
               'images'=>!empty($images) ? $images:input('images_log'),
               'add_time' => !empty($time) ? $time :date('Y-m-d H:i:s',time()),
                'cat_id'=>input('art_cate')

           );
           $res=db('article')->insert($data);
          if($res==true){
              $this->success('添加成功',U('admin/article/article'),2);
          }else{

              $this->error('添加失败',U('admin/article/add_article'),2);
          }

       }
        $this->initEditor();
        return $this->fetch();

    }

    public function Article_cat(){
        $key_word = input('cat_name') ? trim(input('cat_name')) : '';
        $where = "cat_name like '%$key_word%'" ;
        $result = db('article_cat')->where($where)->order('parent_id ASC')->paginate(20);
      if($result==true ){
          $page = $result->render();
          $this->assign('page',$page );
          $res=getTree($result);
          $this->assign('article_cat',$res);
      }

        return $this->fetch();
    }


    public function add_Article_cat(){
            $parentArticle=db('article_cat')->order('parent_id ASC')->select();
            $result=getTree($parentArticle);

           $this->assign('parentArticle', $result);
           $but= input('do_submit');
            if(!empty($but)){
                $name =input('cat_name');
                $cat_name = isset($name) ? input('cat_name'):false;
                $is_show = input('is_show');
                if($cat_name==""){
                $this->error("分类名称不能为空");
                }


             $data=array(
                'cat_name'=>$cat_name,
                 'is_show'=>$is_show,
                 'parent_id'=>input('parent_name'),
                 'addtime'=>time()
             );

          $result = db('article_cat')->insert($data);
          if($result==true){
              $this->success("添加成功",U('admin/article/article_cat'),2);
          }else{
              $this->error("添加失败！");
          }

        }
        return  $this->fetch();
    }


    public function edit_article_cat(){
        $edit_id= input('edit_id');
        $result = db('article_cat')->where('id='.$edit_id)->find();
        $cat_id= input('cat_id');
        if(!empty($cat_id)){
           $cat_name = input('cat_name');
           $is_show= input('is_show');
           $data=array(

               'cat_name'=>$cat_name,
               'is_show'=>$is_show
           );
          $res =db('article_cat')->where('id='.$cat_id)->update($data);
          if($res==true){
              $this->success('修改成功',U('admin/article/article_cat'),2);
          }else{
              $this->error('修改失败！');
          }
        }
        $this->assign('list',$result);
        return  $this->fetch();
    }
    public function del(){

        $cat_id =input('act_id');
        $data=array(
          'id'=>$cat_id
        );
       $res = db('article_cat')->delete($data);
       if($res==true){
             $result=array(
                'status'=>1,
                 'info'=>"删除成功！"
             );
           $this->ajaxReturn($result);

       }else{
           $result=array(
               'status'=>0,
               'info'=>"删除失败！"
           );
           $this->ajaxReturn($result);

       }

    }

}
