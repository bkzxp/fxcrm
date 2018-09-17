<?php /*a:2:{s:66:"E:\wamp64\wamp64\www\cms\application\admin\view\manager\index.html";i:1536744971;s:65:"E:\wamp64\wamp64\www\cms\application\admin\view\index_layout.html";i:1536744971;}*/ ?>
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
                    <a class="layui-btn layui-btn-sm" href="<?php echo url('admin/manager/add'); ?>">添加管理员</a>
                </div>
            </blockquote>
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>操作</th>
                        <th>登录名</th>
                        <th>所属角色</th>
                        <th>最后登录IP</th>
                        <th>最后登录时间</th>
                        <th>E-mail</th>
                        <th>真实姓名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($Userlist) || $Userlist instanceof \think\Collection || $Userlist instanceof \think\Paginator): $i = 0; $__LIST__ = $Userlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo htmlentities($vo['userid']); ?></td>
                        <td>
                            <a class="layui-btn layui-btn-xs" href="<?php echo url('admin/manager/edit',['id'=>$vo['userid']]); ?>">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs ajax-get confirm" url="<?php echo url('admin/manager/del',['id'=>$vo['userid']]); ?>">删除</a>
                        </td>
                        <td><?php echo htmlentities($vo['username']); ?></td>
                        <td><?php  echo model('admin/AuthGroup')->getRoleIdName($vo['roleid'])  ?></td>
                        <td><?php  echo $vo['last_login_ip'] ? long2ip($vo['last_login_ip']) : '--'  ?></td>
                        <td><?php  echo $vo['last_login_time'] ? time_format($vo['last_login_time']) : '--'  ?></td>
                        <td><?php echo htmlentities($vo['email']); ?></td>
                        <td><?php echo htmlentities($vo['nickname']); ?></td>
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