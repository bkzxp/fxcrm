layui.use(['element', 'layer', 'form','laydate'], function() {
    var element = layui.element,
        layer = layui.layer,
        $ = layui.jquery,
        form = layui.form,
        laydate = layui.laydate;


    /*$('[title]').hover(function() {
        var title = $(this).attr('title');
        layer.tips(title, $(this))
    }, function() {
        layer.closeAll('tips')
    })*/

    //常规用法
    laydate.render({
        elem: '#createtime1'
    });



    /* 监听状态设置开关 */
    form.on('switch(switchStatus)', function(data) {
        var that = $(this),
            status = 0;
        if (!that.attr('data-href')) {
            layer.msg('请设置data-href参数');
            return false;
        }
        if (this.checked) {
            status = 1;
        }
        $.get(that.attr('data-href'), { status: status }, function(res) {
            layer.msg(res.msg);
            if (res.code == 1) {
                that.trigger('click');
                form.render('checkbox');
            }
        });
    });

    //ajax get请求
    $('.ajax-get').click(function() {
        var target;
        var that = this;
        if ($(this).hasClass('confirm')) {
            layer.confirm('确认要执行该操作吗?', { icon: 3, title: '提示' }, function(index) {
                if ((target = $(that).attr('href')) || (target = $(that).attr('url'))) {
                    $.get(target).success(function(data) {
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

                }
                layer.close(index);
            });
        };
        return false;
    });

    //通用表单post提交
    $('.ajax-post').on('click', function(e) {
        var target, query, form1;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;

        form1 = $('.' + target_form);
        if ($(this).attr('hide-data') === 'true') {
            form1 = $('.hide-data');
            query = form1.serialize();
        } else if (form1.get(0) == undefined) {
            return false;
        } else if (form1.get(0).nodeName == 'FORM') {
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