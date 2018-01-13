<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
/**
 * @param $data
 */
define("APP_ROOT",dirname(__FILE__));

/***
 * @param $data “查询数据结果集array()”
 * @param int $pId 父DI
 * @param int $level 层级次数
 * @return array|string     返回数组格式
 */
function getTree($data, $pId = 0, $level = 0)
{
    $tree = '';
    $level = $level + 2;
    foreach ($data as $k => $v) {
        if ($v['parent_id'] == $pId) {
            $v['cat_name'] = str_repeat('&nbsp;', $level) . '|-' . $v['cat_name'];
            $v['child'] = getTree($data, $v['id'], $level);
            $tree[] = $v;
        }
    }
    return $tree;
}

function getMenuList()
{
    //根据角色权限过滤菜单
    $role_id = \think\Session::get('role');
    $menu_list = getAllMenu();/**/
    $role = db('admin_role')->where('role_id', $role_id)->find();
    if ($role['role_list'] != 'all') {
        $right = db('system_code')->whereIn('id', $role['role_list'])->field('right')->select();
        $role_right = "";
        foreach ($right as $val) {
            foreach ($val as $key) {
                $role_right .= $key;
            }
        }
        $code = explode(',', $role_right);
        foreach ($menu_list as $k => $mrr) {
            foreach ($mrr['sub_menu'] as $j => $v) {
                $controller = $v['control'];
                $action = $v['act'];
                if (!preg_grep("/$controller@$action/i", $code)) {
                    unset($menu_list[$k]['sub_menu'][$j]);//过滤菜单
                }
            }
        }
    }
    return $menu_list;
}

function getAllMenu()
{
    return array(
        'User' => array('name' => '用户管理', 'icon' => 'fa-cog',
            'sub_menu' => array(
                array('name' => '用户列表', 'act' => 'user_list', 'control' => 'User'),
            )),
        'Role' => array('name' => '权限管理', 'icon' => 'fa-gears',
            'sub_menu' => array(
                array('name' => '权限资源列表', 'act' => 'roleResultList', 'control' => 'Role'),
                array('name' => '管理员列表', 'act' => 'admin_list', 'control' => 'Role'),
                array('name' => '角色列表', 'act' => 'roleList', 'control' => 'Role'),
                //  array('name' => '管理员日志', 'act'=>'log', 'control'=>'Admin'),
            )),
        'Conf' => array('name' => '系统设置', 'icon' => 'fa-gears',
            'sub_menu' => array(
                array('name' => '网站配置', 'act' => 'Conf', 'control' => 'Conf'),

            )),
        'Messages' => array('name' => '留言列表', 'icon' => 'fa-gears',
            'sub_menu' => array(
                array('name' => '留言列表', 'act' => 'Messages', 'control' => 'Messages'),
            )),
        'ads' => array('name' => '广告管理', 'icon' => 'fa-gears',
            'sub_menu' => array(
                array('name' => '广告列表', 'act' => 'ads', 'control' => 'Ads'),
                array('name' => '广告分类', 'act' => 'ads_cat', 'control' => 'Ads')
            )),
        'Article' => array('name' => '文章管理', 'icon' => 'fa-gears',
            'sub_menu' => array(
                array('name' => '文章列表', 'act' => 'article', 'control' => 'Article'),
                array('name' => '文章分类', 'act' => 'article_cat', 'control' => 'Article')
            )),
        'Tools' => array('name' => '数据库管理', 'icon' => 'fa-gears',
            'sub_menu' => array(
                array('name' => '数据库列表', 'act' => 'index', 'control' => 'Tools'),
                array('name' => '备份数据库', 'act' => 'restore', 'control' => 'Tools')
            )),
    );
}


// 应用公共文件
function createNoncestr($length = 6)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * 格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}


/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
        return true;
    return false;
}

function ajaxReturn($data, $type = 'json')
{
    exit(json_encode($data));
}

/**
 * 邮件发送
 * @param $to    接收人
 * @param string $subject 邮件标题
 * @param string $content 邮件内容(html模板渲染后的内容)
 * @throws Exception
 * @throws phpmailerException
 */
function send_email($to, $subject = '', $content = '')
{
    vendor('phpmailer.PHPMailerAutoload'); ////require_once vendor/phpmailer/PHPMailerAutoload.php';
    //判断openssl是否开启
    $openssl_funcs = get_extension_funcs('openssl');
    if (!$openssl_funcs) {
        return array('status' => -1, 'msg' => '请先开启openssl扩展');
    }
    $mail = new PHPMailer;
    $config = ConfigFind();
    $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //调试输出格式
    //$mail->Debugoutput = 'html';
    //smtp服务器
    $mail->Host = $config['email_server'];
    //端口 - likely to be 25, 465 or 587
    $mail->Port = $config['email_smtp'];

    if ($mail->Port === 465) $mail->SMTPSecure = 'ssl';// 使用安全协议
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //用户名
    $mail->Username = $config['email_user'];
    //密码
    $mail->Password = $config['email_code'];
    //Set who the message is to be sent from
    $mail->setFrom($config['email_user']);
    //回复地址
    //$mail->addReplyTo('replyto@example.com', 'First Last');
    //接收邮件方
    if (is_array($to)) {
        foreach ($to as $v) {
            $mail->addAddress($v);
        }
    } else {
        $mail->addAddress($to);
    }

    $mail->isHTML(true);// send as HTML
    //标题
    $mail->Subject = $subject;
    //HTML内容转换
    $mail->msgHTML($content);
    //Replace the plain text body with one created manually
    //$mail->AltBody = 'This is a plain-text message body';
    //添加附件
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send()) {
        return array('status' => -1, 'msg' => '发送失败: ' . $mail->ErrorInfo);
    } else {
        return array('status' => 1, 'msg' => '发送成功');
    }
}

/**
 * 读取配置文件
 * @param
 * @param
 * @return
 */
function ConfigFind()
{

    $conf = db('baseconf')->find();

    return $conf;
}


/**
 * 获取缓存或者更新缓存
 * @param string $config_key 缓存文件名称
 * @param array $data 缓存数据  array('k1'=>'v1','k2'=>'v3')
 * @return array or string or bool
 */
/*function tpCache($config_key,$data = array()){
    $param = explode('.', $config_key);
    if(empty($data)){
        //如$config_key=shop_info则获取网站信息数组
        //如$config_key=shop_info.logo则获取网站logo字符串
        $config = F($param[0],'',TEMP_PATH);//直接获取缓存文件
        if(empty($config)){
            //缓存文件不存在就读取数据库
            $res = db('config')->where("inc_type",$param[0])->select();
            if($res){
                foreach($res as $k=>$val){
                    $config[$val['name']] = $val['value'];
                }
                F($param[0],$config,TEMP_PATH);
            }
        }
        if(count($param)>1){
            return $config[$param[1]];
        }else{
            return $config;
        }
    }else{
        //更新缓存
        $result =  D('config')->where("inc_type", $param[0])->select();
        if($result){
            foreach($result as $val){
                $temp[$val['name']] = $val['value'];
            }
            foreach ($data as $k=>$v){
                $newArr = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
                if(!isset($temp[$k])){
                    M('config')->add($newArr);//新key数据插入数据库
                }else{
                    if($v!=$temp[$k])
                        M('config')->where("name", $k)->save($newArr);//缓存key存在且值有变更新此项
                }
            }
            //更新后的数据库记录
            $newRes = D('config')->where("inc_type", $param[0])->select();
            foreach ($newRes as $rs){
                $newData[$rs['name']] = $rs['value'];
            }
        }else{
            foreach($data as $k=>$v){
                $newArr[] = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
            }
            M('config')->insertAll($newArr);
            $newData = $data;
        }
        return F($param[0],$newData,TEMP_PATH);
    }
}*/


/*//获取用户

//function Navigation($pid=0){
//      $nav = db('article')->alias('a')->join('__ARTICLE_CAT b','a.id=b.cat_id')->field('a.*,b.id as bid ,b.cat_name')->select();
//      foreach ($nav as $key=>$item){
//            $item['cat_id']=NvaChild($item['id']);
//      }
//
//
//}
//
//function NvaChild($child){
//
//     $child_nav=db('article')->where(array('id'=>$child))->find();
//
//     return $child_nav;
//}*/
