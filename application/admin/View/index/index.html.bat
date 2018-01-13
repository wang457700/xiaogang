<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:16:41 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>后台首页</title>

    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico">
    <link href="__STATIC__/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__STATIC__/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__STATIC__/css/animate.min.css" rel="stylesheet">
    <link href="__STATIC__/css/style.min862f.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <span><img alt="image" class="img-circle" style="width: 50px;height: 50px;" src="__PUBLIC__/uploads/{$Think.session.img}" /></span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                               <span class="block m-t-xs"><strong class="font-bold">{$Think.session.username} </strong></span>
                                <span class="text-muted text-xs block">超级管理员<b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a class="J_menuItem" href="form_avatar.html">修改头像</a>
                                </li>
                                <li><a class="J_menuItem" href="profile.html">个人资料</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="{:U('admin/login/logout')}">安全退出</a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">H+
                        </div>
                    </li>
                    <li>
                        <a class="J_menuItem" href="{:U('admin/index/index_right')}"><i class="fa fa-home"></i> <span class="nav-label">首页</span></a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-file-text"></i>
                            <span class="nav-label">用户管理</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="{:U('admin/user/user_list')}">用户列表</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-file-text"></i>
                            <span class="nav-label">网站配置</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="{:U('admin/conf/conf')}">修改配置</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-file-text"></i>
                            <span class="nav-label">文章管理</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="{:U('admin/article/article')}">文章列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="{:U('admin/article/article_cat')}">文章分类</a>
                            </li>

                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-file-text"></i>
                            <span class="nav-label">广告管理</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="{:U('admin/ads/ads')}">广告列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="{:U('admin/ads/ads_cat')}">广告分类</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-file-text"></i>
                            <span class="nav-label">权限管理</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="{:U('admin/Role/admin_list')}">管理员列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="{:U('admin/Role/roleList')}">角色管理</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="{:U('admin/Role/roleResultList')}">权限资源列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="{:U('admin/Role/adminLog')}">管理员日志</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-file-text"></i>
                            <span class="nav-label">数据库管理</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="{:U('admin/Tools/index')}">数据备份</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="{:U('admin/Tools/restore')}">数据还原</a>
                            </li>
                        </ul>
                    </li>
                    <!--<li>
                        <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">产品管理</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="../user/list.html">相机</a>
                            </li>
                            <li><a class="J_menuItem" href="../user/list.html">手机</a>
                            </li>
                            <li>
                                <a href="#">电脑<span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="../list_detail.html">笔记本</a>
                                    </li>
                                    <li><a class="J_menuItem" href="../list_detail.html">台式电脑</a>
                                    </li>
                                    <li><a class="J_menuItem" href="../list_detail.html">平板电脑</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>-->
                    <li>
                        <a class="J_menuItem" href="{:U('admin/messages/messages')}"><i class="fa fa-comments"></i> <span class="nav-label">留言管理</span></a>
                    </li>
                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:void(0);" class="active J_menuTab" data-id="{:U('admin/index/index_right')}">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a href="{:U('admin/login/logout')}" class="roll-nav roll-right J_tabExit"><i class="fa fa fa-sign-out"></i> 退出</a>
        </div>
            <div class="row J_mainContent" id="content-main">
            <!-- 主要框架部分 -->
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="{:U('admin/index/index_right')}" frameborder="0" data-id="{:U('admin/index/index_right')}" seamless></iframe>
            </div>
            <div class="footer">
                <div class="pull-right">&copy; 2009-2017 <a href="#" target="_blank">晓刚博客！MR:Daly</a>
                </div>
            </div>
        </div>
    </div>
    <script src="__STATIC__/js/jquery.min.js?v=2.1.4"></script>
    <script src="__STATIC__/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="__STATIC__/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="__STATIC__/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="__STATIC__/js/plugins/layer/layer.min.js"></script>
    <script src="__STATIC__/js/hplus.min.js?v=4.1.0"></script>
    <script type="text/javascript" src="__STATIC__/js/contabs.min.js"></script>
    <script src="__STATIC__/js/plugins/pace/pace.min.js"></script>
</body>
</html>
