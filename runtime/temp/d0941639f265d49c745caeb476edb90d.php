<?php /*a:2:{s:78:"E:\wamp64\wamp64\www\cms\application\admin\view\auth_manager\managergroup.html";i:1536744971;s:65:"E:\wamp64\wamp64\www\cms\application\admin\view\index_layout.html";i:1536744971;}*/ ?>
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
    <div class="layui-card-header">编辑访问权限</div>
    <div class="layui-card-body">
        <form class="layui-form form-horizontal" action="<?php echo url('admin/AuthManager/writeGroup'); ?>" method="post">
            <div class="zTreeDemoBackground left">
                <ul id="treeDemo" class="ztree"></ul>
            </div>
            <input type="hidden" name="rules" value="" />
            <input type="hidden" name="id" value="<?php echo htmlentities($group_id); ?>" />
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn ajax-post" lay-submit="" lay-filter="*" target-form="form-horizontal">立即提交</button>
                    <button class="layui-btn layui-btn-normal" onClick="javascript :history.back(-1);">返回</button>
                </div>
            </div>
        </form>
    </div>
</div>

    
<link rel="stylesheet" href="/static/zTree/metroStyle.css">
<script>
layui.config({
    base: "/static/zTree/" //ztree放这
}).use(['jquery', 'ztree'], function() {
    var $ = layui.$;
    //配置
    var setting = {
        //设置 zTree 的节点上是否显示 checkbox / radio
        check: {
            enable: true,
            chkboxType: { "Y": "ps", "N": "ps" }
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "nid",
                pIdKey: "parentid",
            }
        },
        callback: {
            beforeClick: function(treeId, treeNode) {
                if (treeNode.isParent) {
                    zTree.expandNode(treeNode);
                    return false;
                } else {
                    return true;
                }
            },
            onClick: function(event, treeId, treeNode) {
                //栏目ID
                var catid = treeNode.catid;
                //保存当前点击的栏目ID
                setCookie('tree_catid', catid, 1);
            }
        }
    };
    //节点数据
    var zNodes = <?php echo $json; ?>;
    //zTree对象
    var zTree = null;
    $(document).ready(function() {
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.expandAll(true);
    });

    layui.use(['element', 'layer', 'form'], function() {
        var element = layui.element,
            layer = layui.layer,
            form = layui.form;
        //通用表单post提交
        $('.ajax-post').on('click', function(e) {
            var target, query, form1;
            var target_form = $(this).attr('target-form');
            var that = this;
            var nead_confirm = false;

            form1 = $('.' + target_form);
            //处理被选中的数据
            form1.find('input[name="rules"]').val("");
            var nodes = zTree.getCheckedNodes(true);
            var str = "";
            $.each(nodes, function(i, value) {
                if (str != "") {
                    str += ",";
                }
                str += value.id;
            });
            form1.find('input[name="rules"]').val(str);

            if (form1.get(0).nodeName == 'FORM') {
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                if ($(this).attr('url') !== undefined) {
                    target = $(this).attr('url');
                } else {
                    //target = form1.get(0).action;
                    target = form1.attr("action");
                }
                query = form1.serialize();
            } else if (form1.get(0).nodeName == 'INPUT' || form1.get(0).nodeName == 'SELECT' || form1.get(0).nodeName == 'TEXTAREA') {
                form1.each(function(k, v) {
                    if (v.type == 'checkbox' && v.checked == true) {
                        nead_confirm = true;
                    }
                })
                if (nead_confirm && $(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                query = form1.serialize();
            } else {
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                query = form1.find('input,select,textarea').serialize();
            }

            $.post(target, query).success(function(data) {
                if (data.code == 1) {
                    if (data.url) {
                        layer.msg(data.msg + ' 页面即将自动跳转~');
                    } else {
                        layer.msg(data.msg);
                    }
                    setTimeout(function() {
                        if (data.url) {
                            location.href = data.url;
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    layer.msg(data.msg);
                    setTimeout(function() {
                        if (data.url) {
                            location.href = data.url;
                        }
                    }, 1500);
                }
            });
            return false;
        });
    });
});
</script>

</body>

</html>