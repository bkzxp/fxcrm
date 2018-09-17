<?php /*a:2:{s:67:"E:\wamp64\wamp64\www\cms\application\admin\view\config\setting.html";i:1536744971;s:65:"E:\wamp64\wamp64\www\cms\application\admin\view\index_layout.html";i:1536744971;}*/ ?>
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
                <li class="<?php if($key==$group): ?>layui-this<?php endif; ?>"><a href="<?php echo url('admin/config/setting',['group'=>$key]); ?>"><?php echo htmlentities($vo); ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="layui-tab-content">
                <form class="layui-form form-horizontal" action="<?php echo url('admin/config/setting',['group'=>$group]); ?>" method="post">
                    <div class="layui-form">
                      <table class="layui-table">
                        <colgroup>
                          <col width="200">
                          <col >
                          <col width="200">
                        </colgroup>
                        <thead>
                          <tr>
                            <th>参数说明</th>
                            <th>参数值</th>
                            <th>变量名</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($fieldList) || $fieldList instanceof \think\Collection || $fieldList instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['type']): case "text": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <input type="text" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]" placeholder="请输入<?php echo htmlentities($vo['title']); ?>" autocomplete="off" class="layui-input" value="<?php echo htmlentities($vo['value']); ?>">
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "number": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <input type="number" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]" placeholder="请输入<?php echo htmlentities($vo['title']); ?>" autocomplete="off" class="layui-input" value="<?php echo htmlentities($vo['value']); ?>">
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "switch": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <input type="checkbox" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]" lay-skin="switch" lay-text="ON|OFF" value="<?php echo htmlentities($vo['value']); ?>" <?php if(1==$vo[ 'value']): ?>checked='' <?php endif; ?>>
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "array": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <textarea name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]" placeholder="请输入<?php echo htmlentities($vo['title']); ?>" class="layui-textarea"><?php echo htmlentities($vo['value']); ?></textarea>
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "checkbox": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <?php if(is_array($vo['options']) || $vo['options'] instanceof \think\Collection || $vo['options'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['options'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                    <input type="checkbox" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>][]" lay-skin="primary" title="<?php echo htmlentities($v); ?>" value="<?php echo htmlentities($key); ?>" <?php if(in_array($key,$vo[ 'value'])): ?>checked='' <?php endif; ?>>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "radio": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <?php if(is_array($vo['options']) || $vo['options'] instanceof \think\Collection || $vo['options'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['options'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                    <input type="radio" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]" value="<?php echo htmlentities($key); ?>" title="<?php echo htmlentities($v); ?>" <?php if($key==$vo [ 'value']): ?>checked='' <?php endif; ?>>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "select": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <select name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]">
                                    <option value=""></option>
                                    <?php if(is_array($vo['options']) || $vo['options'] instanceof \think\Collection || $vo['options'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['options'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo htmlentities($key); ?>" <?php if($key==$vo[ 'value']): ?>selected="" <?php endif; ?>><?php echo htmlentities($v); ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "datetime": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <input type="text" class="layui-input test-item" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]" placeholder="请输入<?php echo htmlentities($vo['title']); ?>" value="<?php echo htmlentities($vo['value']); ?>">
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; case "textarea": ?>
                            <tr>
                            <td><?php echo htmlentities($vo['title']); ?></td>
                            <td>
                                <textarea placeholder="请输入<?php echo htmlentities($vo['title']); ?>" class="layui-textarea" name="<?php echo htmlentities($vo['fieldArr']); ?>[<?php echo htmlentities($vo['name']); ?>]"><?php echo htmlentities($vo['value']); ?></textarea>
                            </td>
                            <td><?php echo htmlentities($vo['name']); ?></td>
                            </tr>
                            <?php break; endswitch; endforeach; endif; else: echo "" ;endif; ?>

                        </tbody>
                      </table>
                    </div>





                    <?php if(count($fieldList)): ?>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn ajax-post" lay-submit="" lay-filter="*" target-form="form-horizontal">立即提交</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

    
<script type="text/javascript" src="/static/admin/js/common.js"></script>
<script>
layui.use(['element', 'form', 'laydate'], function() {
    var form = layui.form,
        element = layui.element,
        $ = layui.jquery,
        laydate = layui.laydate;;
    //同时绑定多个
    lay('.test-item').each(function() {
        laydate.render({
            elem: this,
            trigger: 'click',
            type: 'datetime'
        });
    });

});
</script>

</body>

</html>