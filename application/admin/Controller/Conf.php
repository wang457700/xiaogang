<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/21
 * Time: 15:15
 */
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 10:27
 */
namespace app\admin\Controller;
use think\Request;
use think\Controller;

Class Conf extends Base{

    public function Conf(){
        $conf = db('baseconf')->find();
        $this->assign('conf',$conf);

        $sub = input('do_submit');
        if(!empty($sub)){
            $file = Request::instance()->file('logo');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if(!empty($file)) {
                $info = $file->validate(['size' => 1567800, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $images = $info->getSaveName();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError(), U('admin/conf/conf'), 2);
                }
            }
            $data=array(
              'bese_name'=>input('bese_name'),
               'bese_description'=>input('bese_description'),
                'is_start'=>input('is_start'),
                'is_email_login'=>input('is_email_login'),
                'email_server'=>input('email_server'),
                'email_smtp'=>input('email_smtp'),
                'email_code'=>input('email_code'),
                'email_user'=>input('email_user'),
                'logo'=>isset($images) ? $images :input('logo_log')
            );
          $res = db('baseconf')->where('id=1')->update($data);
          if(!empty($res)){
              $this->error('修改成功');
          }


        }

        return $this->fetch();
    }






}