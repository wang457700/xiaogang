<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/11
 * Time: 10:35
 */
namespace app\admin\Controller;

use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller{

    const URL ="www.baidu.com";
    public function __construct(Request $request = null)
    {


      return  parent::__construct($request);
    }

    /**
     * @deprecated
     * @param
     */

    public function _initialize()
    {
        //过滤不需要登陆的行为

            if(!Session::get('admin')>0){
                $this->error('请先登录!',U('admin/login/login'),2);
            }else{
                $this->checkRight();
            }
        }

    public function ajaxReturn($data,$type = 'json'){
        exit(json_encode($data));
    }
    /**
     * @info 权限验证！
     * @return bool
     */
    public function checkRight(){

        $controller= $this->request->controller();
        $action= $this->request->action();
        //无需验证的操作
        $NoCheck = array('login','logout','modifyPWS');
        $role =db('admin_role')->where('role_id',Session::get('role'))->find();
        if(CONTROLLER_NAME == 'Index' || $role['role_list'] == 'all'  || in_array(ACTION_NAME,$NoCheck)){
            //后台首页控制器无需验证,超级管理员无需验证
            return true;
        }elseif(request()->isAjax() || strpos(ACTION_NAME,'ajax')!== false){
            //所有ajax请求不需要验证权限
            return true;
        }else{
            $system_code=db('system_code')->whereIn('id',$role['role_list'])->field('right')->select();


            $check_code='';
            foreach ($system_code as $item=>$key){
                foreach ($key as $vle){
                    $check_code .=$vle;
                }
            }
            $code=explode(',',$check_code);
/*
             $a= Array ( 0 => 'Messages@Messages' ,1 => 'Messages@del_mes', 2 => 'Messages@delAll_mes' ,3 => 'Messages@status' );

            print_r($code);
            $b= 'Messages@messages';
            $str="''";
            echo $str; echo $b;
            var_dump(preg_grep("/$controller@$action/i", $code));


       die;*/
            if(!preg_grep("/$controller@$action/i", $code)){
                $this->error('您没有操作权限['.(CONTROLLER_NAME.'@'.ACTION_NAME).'],请联系超级管理员分配权限',U('Admin/Index/welcome'));
            }
        }
    }

    /**
     * @return \Redis
     * @TPYE Cache
     */
    public function Redis(){
        $redis=new \Redis();
        $redis->connect(config("REDIS_HOST"),config("REDIS_PORT"));
        return $redis;
    }


}
