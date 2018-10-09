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
        $this->source = array(
            '来源1',
            '来源2',
            '来源3',
            '来源4',
            '来源5',
        );
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
            $userlist_[0]['title'] = $this->_userinfo['nickname'];
        }else{
            $userlist = $this->Vb->getAgents();
            if(empty($userlist)){
                $this->error('没有查到有效的供应商！');
            }
            $userlist_[] = array(
                'id' => '-1',
                'title' => '全部-可输入选择',
            );
            foreach ($userlist as $k => $v){
                $userlist_[] = array(
                    'id' => $v['userid'],
                    'title' => $v['nickname'].'__'.$v['username'],
                );
            }
        }

        //响应查询请求
        $searchwhere['u'] = -1;
        $where = array();
        if ($this->_userinfo['roleid'] != 1) {  //普通用户只能查询自己的记录
            $where[] = ['userid','eq',$this->_userinfo['userid']];
            $searchwhere['u'] = $this->_userinfo['userid'];
            $searchwhere['r'] = 0;  //普通用户
        }else{
            $searchwhere['r'] = 1;  //管理员
            if(isset($_POST['userid']) && $_POST['userid'] != -1){
                $where[] = ['userid','eq',(int)$_POST['userid']];
                $searchwhere['u'] = $_POST['userid'];
            }
        }
        $date = '';
        //列表改版，取消掉创建时间查询
//        if(isset($_POST['createtime']) && $_POST['createtime']){
//            $date = $_POST['createtime'];
//            $star = strtotime($date);
//            $end = $star+86399;
//            $where[] = ['create_time','between', "$star,$end"];
//        }
        $searchwhere['t'] = $date;
        $searchwhere['p'] = 0;
        //列表改版，取消掉操作类型查询
//        if(isset($_POST['type']) && $_POST['type'] != 0){
//            $type = $_POST['type'];
//            if($type == 1){
//                $where[] = ['vb','>', "0"];
//                $searchwhere['p'] = $type;
//            }elseif ($type == -1){
//                $where[] = ['vb','<', "0"];
//                $searchwhere['p'] = $type;
//            }
//        }

        $vbres = $this->Vb->getAgentsList($where, "$limit,$pagesize");

        $this->assign('count', $vbres['count']); //总条数
        $this->assign('pagesize', $pagesize); //页码大小
        $this->assign('page', $page); //当前页

        $this->assign('vblist', $vbres['data']);    //VB记录
        $this->assign('userlist', $userlist_);  //供应商列表
        $this->assign('searchwhere', $searchwhere); //搜索条件
        return $this->fetch();
    }

    //代理商VB记录明细
    public function info(){
        //响应查询请求
        $where = array();
        if ($this->_userinfo['roleid'] != 1) {  //普通用户只能查询自己的记录
            $where[] = ['uid','eq',$this->_userinfo['id']];
        }else{
            $id = $this->request->param('id/d');
            if($id){
                $where[] = ['uid','eq',$id];
            }
        }
        if(empty($where)){  //当条件不符合时
            $where[] = ['uid','eq',0];
        }

        $vbres = $this->Vb->getList($where);
        $this->assign('vblist', $vbres['data']);    //VB记录
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
            if($data['type'] == 1){
                //$data['vb'] = $data['vb'];    /不用操作
            }elseif ($data['type'] == -1){
                $data['vb'] = 0 - $data['vb'];  //取反
            }else{
                return $this->error('操作类型未选择');
            }
            if ($this->Vb->createVb($data)) {
                $this->success("添加VB成功！", url('admin/main/index'));
            } else {
                $error = $this->Vb->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            $this->assign("sourcelist", $this->source);
            $this->assign("agents", model('admin/Vb')->getAgents());
            return $this->fetch();
        }
    }

    //编辑VB
    public function edit(){
        if ($this->request->isPost()) {
            $this->success("VB记录不可编辑！", url('admin/main/index'));
            exit();
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
            $this->assign("sourcelist", $this->source);
            $this->assign("agents", model('admin/Vb')->getAgents());
            return $this->fetch();
        }
    }

}
