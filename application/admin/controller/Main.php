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
// | 后台欢迎页
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Vb as Vb_Model;
use app\common\controller\Adminbase;
use think\Db;

class Main extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Vb = new Vb_Model;
    }

    //欢迎首页
    public function index()
    {
        $where = array();
        if ($this->_userinfo['roleid'] != 1) {  //普通用户只能查询自己的记录
            $where['uid'] = $this->_userinfo['userid'];
        }
        $vblist = $this->Vb->getList($where);
        $this->assign('vblist', $vblist);
        return $this->fetch();
    }

    //添加VB
    public function add(){

    }

}
