<?php /*a:2:{s:71:"E:\wamp64\wamp64\www\cms\application\admin\view\auth_manager\index.html";i:1536744971;s:65:"E:\wamp64\wamp64\www\cms\application\admin\view\index_layout.html";i:1536744971;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>YZNCMS后台管理系统</title>
    <meta name="author" content="YZNCMS">
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <link rel="stylesheet" href="/static/admin/css/admin.css">
    <link rel="stylesheet" href="/static/admin/font/iconfont.css">
    <script src="/static/layui/layui.js"></script>
</head>

<body class="childrenBody">
    
<div class="layui-card">
    <div class="layui-card-body">
        <div class="layui-form">
            <blockquote class="layui-elem-quote news_search">
                <div class="layui-inline">
                    <a class="layui-btn layui-btn-sm" href="<?php echo url('AuthManager/createGroup'); ?>">添加角色</a>
                </div>
            </blockquote>
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>操作</th>
                        <th>权限组</th>
                        <th>描述</th>
                        <th>状态</th>
                        <th>授权</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($_list) || $_list instanceof \think\Collection || $_list instanceof \think\Paginator): $i = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td>
                            <a class="layui-btn layui-btn-xs" href="<?php echo url('AuthManager/editgroup?id='.$vo['id']); ?>">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs ajax-get confirm" url="<?php echo url('AuthManager/deletegroup?id='.$vo['id']); ?>">删除</a>
                        </td>
                        <td><?php echo htmlentities($vo['title']); ?></td>
                        <td><?php echo mb_strimwidth($vo['description'],0,60,"...","utf-8"); ?></td>
                        <td><?php if($vo['status'] == '1'): ?><span class="on"><i class="icon iconfont icon-zhengque1"></i><?php echo htmlentities($vo['status_text']); ?></span> <?php else: ?>
                            <span class="off"><i class="icon iconfont icon-iconfontcuowu2"></i><?php echo htmlentities($vo['status_text']); ?></span> <?php endif; ?>
                        </td>
                        <td>
                            <a class="layui-btn layui-btn-xs" href="<?php echo url('AuthManager/access?group_name='.$vo['title'].'&group_id='.$vo['id']); ?>">访问授权</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    
<script type="text/javascript" src="/static/admin/js/common.js"></script>

</body>

</html>