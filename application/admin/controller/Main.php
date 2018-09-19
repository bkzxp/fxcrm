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
        $page = 1;  //请求页
        $pagesize = 10;  //页码大小
        $limit = 0; //记录起始

        if(isset($_POST['page']) && (int)$_POST['page'] && isset($_POST['fenye']) && $_POST['fenye']==1){   //当时fenye请求时page参数才有效
            $page = (int)$_POST['page'];
            $limit = ($page-1)*$pagesize;
        }

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
            $searchwhere['r'] = 0;  //普通用户
        }else{
            $searchwhere['r'] = 1;  //管理员
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

        $vbres = $this->Vb->getList($where, "$limit,$pagesize");

        $this->assign('count', $vbres['count']); //总条数
        $this->assign('pagesize', $pagesize); //页码大小
        $this->assign('page', $page); //当前页

        $this->assign('vblist', $vbres['data']);    //VB记录
        $this->assign('userlist', $userlist_);  //供应商列表
        $this->assign('searchwhere', $searchwhere); //搜索条件
        $this->assign('total', $vbres['total']);    //满足查询条件的总VB数量
        return $this->fetch();
    }

    //添加VB
    public function add(){
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            $result = $this->validate($data, 'Main.add');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Vb->createVb($data)) {
                $this->success("添加VB成功！", url('admin/main/index'));
            } else {
                $error = $this->Vb->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            $this->assign("agents", model('admin/Vb')->getAgents());
            return $this->fetch();
        }
    }

    //编辑VB
    public function edit(){
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            $result = $this->validate($data, 'Main.edit');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->Vb->editVb($data)) {
                $this->success("修改成功！", url('admin/main/index'));
            } else {
                $this->error($this->User->getError() ?: '修改失败！');
            }
        } else {
            $id = $this->request->param('id/d');
            $data = $this->Vb->where(array("id" => $id))->find();

            if (empty($data)) {
                $this->error('该信息不存在！');
            }
            $this->assign("data", $data);
            $this->assign("agents", model('admin/Vb')->getAgents());
            return $this->fetch();
        }
    }

}
