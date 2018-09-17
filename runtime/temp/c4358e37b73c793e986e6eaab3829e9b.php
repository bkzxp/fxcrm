<?php /*a:2:{s:65:"E:\wamp64\wamp64\www\cms\application\admin\view\config\index.html";i:1536744971;s:65:"E:\wamp64\wamp64\www\cms\application\admin\view\index_layout.html";i:1536744971;}*/ ?>
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
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                <?php if(is_array($groupArray) || $groupArray instanceof \think\Collection || $groupArray instanceof \think\Paginator): $i = 0; $__LIST__ = $groupArray;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="<?php if($key==$group): ?>layui-this<?php endif; ?>"><a href="<?php echo url('index',['group'=>$key]); ?>"><?php echo htmlentities($vo); ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="layui-tab-content">
                <blockquote class="layui-elem-quote news_search">
                    <div class="layui-inline">
                        <a class="layui-btn layui-btn-sm ajax-jump" url="<?php echo url('admin/config/add'); ?>">新增配置项</a>
                    </div>
                </blockquote>
                <div class="layui-tab-item layui-show">
                    <table class="layui-table layui-form layui-table-view">
                        <colgroup>
                            <col width="200">
                            <col>
                            <col width="120">
                            <col width="250">
                            <col width="100">
                            <col width="100">
                            <col width="150">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>名称</th>
                                <th>标题</th>
                                <th>类型</th>
                                <th>更新时间</th>
                                <th>排序</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td><?php echo htmlentities($vo['name']); ?></td>
                                <td><?php echo htmlentities($vo['title']); ?></td>
                                <td><?php echo htmlentities($vo['ftitle']); ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$vo['update_time']); ?></td>
                                <td><?php echo htmlentities($vo['listorder']); ?></td>
                                <td>
                                    <input type="checkbox" <?php if($vo['status']): ?>checked<?php endif; ?> lay-skin="switch" lay-filter="switchStatus" lay-text="开启|关闭" value="<?php echo htmlentities($vo['status']); ?>" data-href="<?php echo url('admin/config/setstate',['id'=>$vo['id']]); ?>">
                                </td>
                                <td>
                                    <a class="layui-btn layui-btn-xs ajax-jump" url="<?php echo url('admin/config/edit',['id'=>$vo['id']]); ?>">编辑</a>
                                    <a class="layui-btn layui-btn-danger layui-btn-xs ajax-get confirm" url="<?php echo url('admin/config/del',['id'=>$vo['id']]); ?>">删除</a>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="barTool">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" url="<?php echo url('admin/config/del'); ?>">删除</a>
</script>
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="sex" value="{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="status" {{ d.status==1 ? 'checked' : '' }}>
</script>

    
<script type="text/javascript" src="/static/admin/js/common.js"></script>

</body>

</html>