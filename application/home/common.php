<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Motto:不求远方，但求心安。做一个追寻自由的追梦人！
// +----------------------------------------------------------------------
// | Author: MR:Daly <457700516@qq.com>
// +----------------------------------------------------------------------

// 应用公共文件

/***
 * @param $data           “查询数据结果集array()”
 * @param int $pId          父DI
 * @param int $level        层级次数
 * @return array|string     返回数组格式
 */
function getTree($data, $pId=0,$level=0)
{
    $tree = '';
    $level=$level+2;
    foreach($data as $k => $v)
    {
        if($v['parent_id'] == $pId)
        {
            $v['cat_name']=str_repeat('&nbsp;',$level).'|-'.$v['cat_name'];
            $v['child'] = getTree($data, $v['id'],$level);
            $tree[] = $v;
        }
    }
    return $tree;
}
/**
 * 数组分页函数 核心函数 array_slice
 * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
 * $count  每页多少条数据
 * $page  当前第几页
 * $array  查询出来的所有数组
 * order 0 - 不变   1- 反序
 */
function page_array($count,$page,$array,$order){
    global $countpage; #定全局变量
    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
    $start=($page-1)*$count; #计算每次分页的开始位置
    if($order==1){
        $array=array_reverse($array);
    }
    $totals=count($array);
    $countpage=ceil($totals/$count); #计算总页面数
    $pagedata=array();
    $pagedata=array_slice($array,$start,$count);
    return $pagedata; #返回查询数据
}

/**
 * 分页及显示函数
 * $countpage 全局变量，照写
 * $url 当前url
 */
function show_array($countpage,$url){
    $page=empty($_GET['page'])?1:$_GET['page'];
    if($page > 1){
        $uppage=$page-1;
    }else{
        $uppage=1;
    }
    if($page < $countpage){
        $nextpage=$page+1;

    }else{
        $nextpage=$countpage;
    }
    $str='<div style="border:1px; width:300px; height:30px; color:#9999CC">';
    $str.="<span>共 {$countpage} 页 / 第 {$page} 页</span>";
    $str.="<span><a href='$url?page=1'>  首页 </a></span>";
    $str.="<span><a href='$url?page={$uppage}'> 上一页 </a></span>";
    $str.="<span><a href='$url?page={$nextpage}'>下一页 </a></span>";
    $str.="<span><a href='$url?page={$countpage}'>尾页 </a></span>";
    $str.='</div>';
    return $str;
}


/**
 ** 截取中文字符串
 **/
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr")){
        $slice= mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
        $slice= iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    $fix='';
    if(strlen($slice) < strlen($str)){
        $fix='...';
    }
    return $suffix ? $slice.$fix : $slice;
}
/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email){
    if(filter_var($email,FILTER_VALIDATE_EMAIL))
        return true;
    return false;
}

function ajaxReturn($data,$type = 'json'){
    exit(json_encode($data));
}

/**
 * 邮件发送
 * @param $to    接收人
 * @param string $subject   邮件标题
 * @param string $content   邮件内容(html模板渲染后的内容)
 * @throws Exception
 * @throws phpmailerException
 */
function send_email($to,$subject='',$content=''){
    vendor('phpmailer.PHPMailerAutoload'); ////require_once vendor/phpmailer/PHPMailerAutoload.php';
    //判断openssl是否开启
    $openssl_funcs = get_extension_funcs('openssl');
    if(!$openssl_funcs){
        return array('status'=>-1 , 'msg'=>'请先开启openssl扩展');
    }
    $mail = new PHPMailer;
    $config = ConfigFind();
    $mail->CharSet  = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
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

    if($mail->Port === 465) $mail->SMTPSecure = 'ssl';// 使用安全协议
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
    if(is_array($to)){
        foreach ($to as $v){
            $mail->addAddress($v);
        }
    }else{
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
        return array('status'=>-1 , 'msg'=>'发送失败: '.$mail->ErrorInfo);
    } else {
        return array('status'=>1 , 'msg'=>'发送成功');
    }
}
/**
 * 读取配置文件
 * @param
 * @param
 * @return
 */
function ConfigFind(){

    $conf=db('baseconf')->find();

    return $conf;
}



function GetOne($table,$cat_id,$id){

    $res=db($table)->where("$cat_id=$id")->find();

    return $res;
}
/**
 ** 截取中文字符串
 **/
/*function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr")){
        $slice= mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
        $slice= iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    $fix='';
    if(strlen($slice) < strlen($str)){
        $fix='...';
    }
    return $suffix ? $slice.$fix : $slice;
}*/