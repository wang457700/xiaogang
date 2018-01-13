<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 11:47
 */
namespace app\admin\Controller;

use app\admin\Validate\UserReg;
use think\Controller;
use  think\File;
use think\Request;
use think\Validate;
use think\Db;
use PHPExcel_IOFactory;
use PHPExcel;

Class User extends Base
{
    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 查询管理员列表
     */
    public function user_list()
    {


        $where = array();
        $keywords = trim(input('keywords'));
        $keywords && $where['nickname'] = array('like', '%' . $keywords . '%');
        $res = db('user')->where($where)->paginate(5);

        $page = $res->render();
        $this->assign('page',$page);
        $this->assign('admin_list', $res);
        return $this->fetch();
    }

    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 删除管理员
     */
    public function del_user()
    {
        $id = input('id');
        $res = db('user')->where('user_id='.$id)->delete();
        if ($res == true) {
            $data = array(
                'status' => 1,
                'info' => '删除成功',
            );
            $this->ajaxReturn($data);
        } else {
            $data = array(
                'status' => 0,
                'info' => '删除失败',
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
    public function delAll_user()
    {
        $id = $_REQUEST['id'];

        if (is_array($id)) {
            $where = 'user_id in(' . implode(',', $id) . ')';
        } else {
            $where = 'user_id=' . $id;
        }

        /*$res = db('user')->where($where)->select();

        foreach ($res as $key) {
            if ($key['role_id'] == '1') {
                $adminsuper = 1;
            }
        }
        if (isset($adminsuper)) {
            $data = array(
                'status' => 0,
                'info' => '不能删除超级管理员',
            );
            $this->ajaxReturn($data);
        }*/

        $res = db('user')->where($where)->delete();

        if ($res == true) {
            $data = array(
                'status' => 1,
                'info' => '删除成功',
            );
            $this->ajaxReturn($data);

        } else {
            $data = array(
                'status' => 0,
                'info' => '删除失败!',
            );
            $this->ajaxReturn($data);

        };
    }

    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 添加管理员
     */
    public function add_user()
    {
        $sub = input('do_submit');
        $username = input('nickname');
        if (!empty($sub)) {

            $is_user = db('user')->where('nickname=' . "'$username'")->find();
            if ($is_user == true) {
                $this->error('当前用户名已经存在', U('admin/user/add_user'), 2);
            }
      /*      $file = Request::instance()->file('user_img');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if (!empty($file)) {
                $info = $file->validate(['size' => 15678, 'ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $images = $info->getSaveName();
                } else {
                    // 上传失败获取错误信息
                    $this->error($file->getError(), U('admin/user/add_user'), 2);
                }
            }*/

            $time = input('addtime');
            $data = array(
                'nickname' => input('nickname'),
                'password' => md5(input('password')),
                'six' => input('six'),
                'mobile_phone' => input('phone'),
                'addtime' => isset($time) ? $time : date('Y-m-d H:i:s', time()),
            );
            if (input('password') != input('password2')) {

                $this->error('二次密码不一致！', U('admin/user/add_user'));
            }
            $username = trim(input('nickname'));
            if (empty($username)) {
                $this->error('账号不能为空！', U('admin/user/add_user'));
            }
            if (!preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/', input('mobile_phone'))) {
                $this->error('手机格式有误！', U('admin/user/add_user'));
            }

            /*       $validate = \think\Loader::validate('UserReg');
                   if(!$validate->batch()->check($data))
                   {
                       $error = $validate->getError();
                       $error_msg = array_values($error);
                       $this->error($error_msg,U('admin/user/add_user'),2);
                   }else{

                       $User= db('admin')->insert($data);
                       $this->success('添加成功',U('admin/user/user_list'),2);
                   }*/

            $AddUser = new UserReg();
            $res = $AddUser->Clik($data);
            if ($res == true) {
                $this->success('添加成功', U('admin/user/user_list'), 2);
            } else {

                $this->success($AddUser->getError(), U('admin/user/add_user'), 2);
            }
        }
        return $this->fetch();

    }

    /**
     * @return mixed
     * @date 2017/11/15
     * @author MR:DALY
     * @info 修改管理员
     */
    public function edit_user()
    {
        $id = input('id');
        if ($id < 0) {
            $this->error('非法提交数据', U('admin/user/user_list'), 2);
        }
        $user_info = db('user')->where('user_id=' . $id)->find();


        $this->assign('user', $user_info);
        $sub = input('do_submit');

        if (!empty($sub)) {

            $time = input('addtime');
            if (!preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/', input('mobile_phone'))) {
                $this->error('手机格式有误！', U('admin/user/add_user'));
            }

            $data = array(
                'nickname' => input('nickname'),
                'password' => input('password'),
                'six' => input('six'),
                'mobile_phone' => input('mobile_phone'),
                'addtime' => !empty($time) ? $time : date('Y-m-d H:i:s', time()),
            );
            /*       $validate = \think\Loader::validate('UserReg');
                   if(!$validate->batch()->check($data))
                   {
                       $error = $validate->getError();
                       $error_msg = array_values($error);
                       $this->error($error_msg,U('admin/user/add_user'),2);
                   }else{

                       $User= db('admin')->insert($data);
                       $this->success('添加成功',U('admin/user/user_list'),2);
                   }*/


            $User = db('user')->where('user_id=' . $id)->update($data);
            if ($User == true) {
                $this->success('修改成功', U('admin/user/user_list'), 2);
            } else {

                $this->success('编辑失败！');
            }
        }
        return $this->fetch();

    }

   public function exportUser(){

        $excelUser=Db::table('run_user')->field('nickname,mobile_phone,addtime,six')->select();
        $title=array( '用户名','手机','注册时间','性别');
        $filename='用户档案'.time();
        $filepath ='./public/excel/';
       if (! file_exists ( $filepath )) {
           mkdir ( "$filepath", 0777, true );
       }
        foreach($excelUser as $key=>$item){
              if($item['six']==1){
                  $excelUser[$key]['six']='女';
              }else{
                  $excelUser[$key]['six']='男';
              }
        }
       $this->exportExcel($title, $excelUser, $filename,  $filepath, true);
    }

    /**
     * @param array $title 标题
     * @param array $data  数据
     * @param string $fileName 文件名称
     * @param string $savePath 保存路径
     * @param bool $isDown
     * @return string
     * @author MR:daly
     * @Data 2018/1/13
     */

    function exportExcel($title=array(), $data=array(), $fileName='', $savePath='./', $isDown=false){
        $obj = new PHPExcel();
        //横向单元格标识
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $obj->getActiveSheet(0)->setTitle('sheet名称');   //设置sheet名称
        $_row = 1;   //设置纵向单元格标识
        if($title){
            $_cnt = count($title);
            $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[$_cnt-1].$_row);   //合并单元格
            $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '数据导出：'.date('Y-m-d H:i:s'));  //设置合并后的单元格内容
            $_row++;
            $i = 0;
            foreach($title AS $v){   //设置列标题
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);
                $i++;
            }
            $_row++;
        }
        //填写数据
        if($data){
            $i = 0;
            foreach($data AS $_v){
                $j = 0;
                foreach($_v AS $_cell){
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);
                    $j++;
                }
                $i++;
            }
        }
        //文件名处理
        if(!$fileName){
            $fileName = uniqid(time(),true);
        }


        $objWrite = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        if($isDown){
            header("Content-type: application/vnd.ms-excel");
            header("Content-Type: application/force-download");
            header('Expires:0');
            header('pragma:public');
            header("Content-Disposition:attachment;filename=$fileName.xls");
            $objWrite->save('php://output');
            $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码
            $_savePath = $savePath.$_fileName.'.xlsx';
            $objWrite->save($_savePath);exit;
        }
        return $savePath.$fileName.'.xlsx';
    }

}

