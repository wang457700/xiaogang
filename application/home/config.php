<?php
/**
 * Created by PhpStorm.
 * User:MR:Dailey
 * Weixin-tel:18665585707
 * Date: 2017/11/10
 * Time: 15:30
 */
return [
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => './template/pc/xiaogang/',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '<',
        // 标签库标签结束标记
        'taglib_end'   => '>',
        //模板文件名
        'default_theme'     => 'xiaogang',
        'dispatch_error_tmpl' => './template/pc/xiaogang/dispatch_jump',
        //默认成功跳转对应的模板文件
        'dispatch_success_tmpl' => './template/pc/xiaogang/dispatch_jump',
    ],
    'view_replace_str'  =>  [
        '__PUBLIC__'=>'/public',
        '__STATIC__' => '/template/pc/xiaogang/static',
        '__ROOT__'=>''
    ]


];