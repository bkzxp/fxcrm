<?php

namespace app\admin\validate;
use \think\Validate;

class Main extends Validate{
    //定义验证规则
    protected $rule = [
        'vb' => 'require|number',
    ];

    //定义验证提示
    protected $message = [
        'vb.number' => 'VB必须是整数',
        'vb.require' => '所持VB不能为空',
    ];

    //定义验证场景
    protected $scene = [
        'add' => ['vb'],
        'edit' => ['vb'],
    ];
}