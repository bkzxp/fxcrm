<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 后台首页
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;

class Index extends Adminbase
{
    //后台首页
    public function index()
    {
        $this->assign('userInfo', $this->_userinfo);
        $menulist = model("admin/Menu")->getMenuList();
        if ($this->_userinfo['roleid'] != 1) {  //普通用户只能查询记录
            foreach ($menulist as $key => $value){  //过滤掉管理菜单
                if($value['menuid'] == 3 || $value['title'] == '后台管理'){
                    unset($menulist[$key]);
                }
            }
        }
        $this->assign("SUBMENU_CONFIG", json_encode($menulist));
        return $this->fetch();
    }

}
