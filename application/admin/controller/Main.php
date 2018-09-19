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
        //生成查询条件
        $userlist_ = array();
        if ($this->_userinfo['roleid'] != 1) {
            $userlist_[0]['id'] = $this->_userinfo['userid'];
            $userlist_[0]['title'] = $this->_userinfo['userno'].'__'.$this->_userinfo['nickname'];
        }else{
            $userlist = Db::name("admin")->field('userid,roleid,nickname,userno')->where(array())->order(array('userno' => 'ASC'))->select();
            if(empty($userlist)){
                $this->error('没有查到有效的供应商！');
            }
            $userlist_[] = array(
                'id' => '-1',
                'title' => '全部',
            );
            foreach ($userlist as $k => $v){
                if($v['roleid'] == 1){
                    continue;
                }
                $userlist_[] = array(
                    'id' => $v['userid'],
                    'title' => $v['userno'].'__'.$v['nickname'],
                );
            }
        }

        //响应查询请求
        $searchwhere['u'] = -1;
        $where = array();
        if ($this->_userinfo['roleid'] != 1) {  //普通用户只能查询自己的记录
            $where[] = ['uid','eq',$this->_userinfo['userid']];
            $searchwhere['u'] = $this->_userinfo['userid'];
        }else{
            if(isset($_POST['userid']) && $_POST['userid'] != -1){
                $where[] = ['uid','eq',(int)$_POST['userid']];
                $searchwhere['u'] = $_POST['userid'];
            }
        }
        $date = '';
        if(isset($_POST['createtime']) && $_POST['createtime']){
            $date = $_POST['createtime'];
            $star = strtotime($date);
            $end = $star+86399;
            $where[] = ['create_time','between', "$star,$end"];
        }
        $searchwhere['t'] = $date;

        $vblist = $this->Vb->getList($where);
        $this->assign('vblist', $vblist);
        $this->assign('userlist', $userlist_);
        $this->assign('searchwhere', $searchwhere);
        return $this->fetch();
    }

    //添加VB
    public function add(){

    }

}
