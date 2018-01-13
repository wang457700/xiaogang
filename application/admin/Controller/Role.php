<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/12/13
 * Time: 15:50
 */
namespace app\admin\Controller;

use think\Controller;
use think\Db;
use think\Request;

class Role extends Base
{

    /**********************************************************************************************************************************
     * @info 管理员板块 查  admin_list() 列表 || admin_info('add','edit') 赋值条件 || adminHandle(管理 add 增加 edit 编辑  del删除)
     * @author MR:Daly
     * todo::超级管理板块
     * @date:2017/12/14
     *********************************************************************************************************************************/
    /**
     * @info 管理员列表
     * @return array();
     * @author MR:Daly
     * @data 2017/12/13
     */
    public function admin_list()
    {
        $where = array();
        $keywords = trim(input('keywords'));
        $keywords && $where['username'] = array('like', '%' . $keywords . '%');
        $res = db('admin')->alias('a')->join('__ADMIN_ROLE__ b ','b.role_id= a.role_id')->whereOR($where)->paginate(5);
        $page = $res->render();
        $this->assign('page',$page);
        $this->assign('list',$res);
        return $this->fetch();

    }

    /**
     * @info 管理员列表 add 添加 edit 编辑
     * @return array();
     * @author MR:Daly
     * @data 2017/12/13
     */
    public function admin_info(){
        error_reporting(E_ERROR | E_PARSE );
        $admin_id = I('get.id/d',0);
        if($admin_id){
            $info = db('admin')->where("id", $admin_id)->find();
            $this->assign('info',$info);
        }/*else{
            $this->assign('info',array('id'=>"",'role_id'=>"",'username'=>"",'add_time'=>time(),'password'=>"",'phone'=>"",'user_email'=>"",'user_desc'=>""));
        }*/
        $act = empty($admin_id) ? 'add' : 'edit';
        $this->assign('act',$act);
        $role = db('admin_role')->select();
        $this->assign('role',$role);
       return  $this->fetch();

    }

    /**
     * @info 管理 add 增加 edit 编辑  del删除
     * @data 2017/12/14
     * @author MR:Daly
     */
    public function adminHandle(){

        $username =input('username');
        $id =input('id');
        $time=input('add_time');
        $file = Request::instance()->file('user_img');
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
        $data = array(
            'role_id'=>input('role_id'),
            'username'=>input('username'),
            'add_time'=>!empty($time) ? $time :date('Y-m-d H:i:s',time()),
            'password'=>md5(input('password')),
            'phone'=>input('phone'),
            'user_email'=>input('user_email'),
            'user_img'=>!empty($images) ? $images:input('user_img_log'),
            'user_desc'=>input('user_desc')
        );

        if(input('act') == 'add'){
            unset($id);
            $data['add_time'] = time();
            if(db('admin')->where("username", $username)->count()){
                $this->error("此用户名已被注册，请更换",U('Admin/Admin/admin_info'));
            }else{
                $result= db('admin')->insert($data);
            }
        }

        if(input('act') == 'edit'){
            $result= db('admin')->where('id', input('id'))->update($data);
        }

        if(input('act') == 'del' && input('id')>1){
            $result = db('admin')->where('id', input('id'))->delete();
            exit(json_encode(1));
        }
        if($result){
            $this->success("操作成功",U('Admin/Role/admin_list'));
        }else{
            $this->error("操作失败",U('Admin/Role/admin_list'));
        }
    }
/**********************************************************************************************************************************
 * @info 权限的曾删改查
 * @author MR:Daly
 * todo::权限板块
 * @date:2017/12/14
 *********************************************************************************************************************************/
    /**
     * @info 权限列表
     * @author MR:Daly
     * @return array();
     * @data 2017/12/13
     */
    public function roleList()
    {

        $RoleList= db('admin_role')->select();
        $this->assign('list',$RoleList);
        return $this->fetch();
    }

    /**
     * @info 权限列表  roleInfo() 添加 修改
     * @author MR:Daly
     * @return array();
     * @data 2017/12/13
     */
    public function roleInfo(){
        error_reporting(E_ERROR | E_PARSE );
      $role_id = input('role_id');
        $detail = array();
        if($role_id){
            $detail = db('admin_role')->where("role_id",$role_id)->find();
            $detail['role_list'] = explode(',', $detail['role_list']);
            $this->assign('info',$detail);
        }
        /*
       $act = empty($role_id) ? 'add' : 'edit';
       if(input('act')=='add'){
            $data= input('post.');
            $roleAdd=db('admin_role')->insert($data);
           if($roleAdd){
              $this->success('添加成功');
           }
       }elseif(input('act')=="edit"){
           $roleId=input('role_id');
           $data=array(
               'role_name'=>input('role_name'),
               'role_desc'=>input('role_desc'),
           );
           $save=db('admin_role')->where('role_id',$roleId)->update($data);
           if($save){
               $this->success('修改成功',U('Role/roleList'));
           }
       }*/
        $modules=array();
        $right = db('system_code')->order('id')->select();
        foreach ($right as $val){
            if(!empty($detail)){
                $val['enable'] = in_array($val['id'], $detail['role_list']);
            }
            $modules[$val['group']][] = $val;
        }
        $group = array(
            'config' => '网站基本配置',
            'userAdmin' => '用户管理',
            'article' => '文章管理',
            'ads' => '广告管理',
            'authAdmin' => '权限管理',
            'databese' => '数据库管理',
            'message' => '留言管理'
        );
        $this->assign('modules',$modules);
        $this->assign('group',$group);
        //$this->assign('act',$act);
        return $this->fetch();
    }
    public function roleSave(){
        $data = I('post.');
        $res = $data['data'];
        $res['role_list'] = is_array($data['right']) ? implode(',', $data['right']) : '';
        if(empty($res['role_list']))
            $this->error("请选择权限!");
        if(empty($data['role_id'])){
            $admin_role = db('admin_role')->where(['role_name'=>$res['role_name']])->find();
            if($admin_role){
                $this->error("已存在相同的角色名称!");
            }else{
                $r = db('admin_role')->insert($res);
            }
        }else{
            $admin_role = db('admin_role')->where(array('role_name'=>$res['role_name'],'role_id'=>['<>',$data['role_id']]))->find();
            if($admin_role){
                $this->error("已存在相同的角色名称!");
            }else{
                $r = db('admin_role')->where('role_id', $data['role_id'])->update($res);
            }
        }
        if($r){

            $this->success("操作成功!",U('Admin/Role/roleList',array('role_id'=>$data['role_id'])));
        }else{
            $this->error("操作失败!",U('Admin/Role/roleInfo'));
        }
    }



    /**********************************************************************************************************************************
     * @info 权限的曾删改查  roleResultList() 查询 roleResultInfo()增加 删除 修改
     * @author MR:Daly
     * todo::权限资源板块
     * @date:2017/12/14
     *********************************************************************************************************************************/
    public function roleResultList()
    {
        $result=db('system_code')->where('is_del=0')->paginate(8);
         $page=$result->render();
        $this->assign('page',$page);
        $this->assign('list',$result);
        return $this->fetch();
    }

    /**
     * @return json int
     * @info roleResultDel() 删除
     */
    public function roleResultDel(){
        if(input('act')=='del'){
             $id=input('id');
            $res=db('system_code')->where('id',$id)->setField('is_del',1);
              if($res){
                  echo json_encode(1);
              }
        }
    }

    /***********************************
     * @return mixed
     * @author MR:Daly tel::18665585707     QQ::457700516
     * @info 添加修改权限资源
     ************************************/

    public function roleResultInfo()
    {
        error_reporting(E_ERROR | E_PARSE );
        if(IS_POST){
            $data = input('post.');
            $data['right'] = implode(',',$data['right']);
            if(!empty($data['id'])){
                db('system_code')->where(array('id'=>$data['id']))->update($data);
                $this->success('更新成功！',U('role/roleResultList'));
            }else{
                if(db('system_code')->where(array('name'=>$data['name']))->count()>0){
                    $this->error('该权限名称已添加，请检查',U('role/roleResultInfo'));
                }
                unset($data['id']);
                $AddRes=db('system_code')->insert($data);
                if($AddRes){
                    $this->success('添加成功，are you ok',U('role/roleResultList'));
                }
            }
            $this->success('操作成功',U('role/roleResultList'));
        }
        $group = array(
            'config' => '网站基本配置',
            'userAdmin' => '用户管理',
            'article' => '文章管理',
            'ads' => '广告管理',
            'authAdmin' => '权限管理',
            'databese' => '数据库管理',
            'message' => '留言管理'
        );
        $planPath = APP_PATH.'admin/controller';
        $planList = array();
        $dirRes   = opendir($planPath);
        while($dir = readdir($dirRes))
        {
            if(!in_array($dir,array('.','..','.svn')))
            {
                $planList[] = basename($dir,'.php');
            }
        }
        $id = input('id');
        if($id){
            $info = db('system_code')->where(array('id'=>$id))->find();
            $info['right'] = explode(',', $info['right']);
            $this->assign('info',$info);
        }/*else{
            $this->assign('info',array('id'=>''));
               todo:: 取消 error_reporting(E_ERROR | E_PARSE ); 可以使用数组的方式替换掉下标没写完自行补上
        }*/
        $act = empty($role_id) ? 'add' : 'edit';
        $this->assign('planList',$planList);
        $this->assign('group',$group);
        return $this->fetch();
    }

    /**@info ajax
     * @return  array html
     */


    function ajax_get_action()
    {
        error_reporting(E_ERROR | E_PARSE );
        // 用法::
        // $a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
        // $a2=array("e"=>"red","f"=>"green","g"=>"blue");
        //array_diff()比较两个数组的键值，并返回差集：
        $control = input('controller');
        $advContrl = get_class_methods("app\\admin\\controller\\".str_replace('.php','',$control)); //返回由 class_name 指定的类中定义的方法名所组成的数组。如果出错，则返回 NULL。
        $baseContrl = get_class_methods('app\admin\controller\Base');
        $diffArray  = array_diff($advContrl,$baseContrl);
        $html = '';
        foreach ($diffArray as $val){
            $html .= "<option value='".$val."'>".$val."</option>";
        }
        exit($html);
    }


    /**********************************************************************************************************************************
     * @info 管理员日志列表
     * @author MR:Daly
     * todo::管理员日志板块
     * @date:2017/12/14
     *********************************************************************************************************************************/

    public function adminLog(){

        return $this->fetch();
    }

}