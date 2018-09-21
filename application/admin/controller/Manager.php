<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\AdminUser;
use app\common\controller\Adminbase;
use think\Db;

/**
 * 管理员管理
 */
class Manager extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->AdminUser = new AdminUser;
    }

    /**
     * 管理员管理列表
     */
    public function index()
    {
        $page = 1;  //请求页
        $pagesize = 10;  //页码大小
        $limit = 0; //记录起始

        if(isset($_POST['page']) && (int)$_POST['page'] && isset($_POST['fenye']) && $_POST['fenye']==1){   //当时fenye请求时page参数才有效
            $page = (int)$_POST['page'];
            $limit = ($page-1)*$pagesize;
        }

        //响应查询请求
        $searchwhere['r'] = -1;
        $searchwhere['u'] = $searchwhere['n'] = '';
        $where = array();
        $where[] = ['status','>',0];
        if(isset($_POST['roleid']) && $_POST['roleid'] != -1){
            $where[] = ['roleid','eq',(int)$_POST['roleid']];
            $searchwhere['r'] = $_POST['roleid'];
        }
        if(isset($_POST['username']) && $_POST['username']){
            $where[] = ['username','like','%'.$_POST['username'].'%'];
            $searchwhere['u'] = $_POST['username'];
        }
        if(isset($_POST['nickname']) && $_POST['nickname']){
            $where[] = ['nickname','like','%'.$_POST['nickname'].'%'];
            $searchwhere['n'] = $_POST['nickname'];
        }

        //角色列表
        $rolelist = array(
            ['id'=>-1, 'title'=>'全部角色']
        );
        $role = Db::name("auth_group")->where('status','eq','1')->select();
        if(!empty($role)){
            foreach ($role as $item) {
                $rolelist[] = ['id'=>$item['id'], 'title'=>$item['title']];
            }
        }

        $User = Db::name("admin")->where($where)->order(array('roleid' => 'ASC'))->limit($limit)->select();
        $count = Db::name("admin")->where($where)->count();

        $this->assign('count', $count); //总条数
        $this->assign('pagesize', $pagesize); //页码大小
        $this->assign('page', $page); //当前页

        $this->assign("Userlist", $User);   //用户列表
        $this->assign('rolelist', $rolelist);  //角色列表
        $this->assign('searchwhere', $searchwhere); //搜索条件
        return $this->fetch();
    }

    /**
     * 添加管理员
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            if($data['roleid'] == 1){
                $result = $this->validate($data, 'AdminUser.insertadmin');
            }else{
                $result = $this->validate($data, 'AdminUser.insert');
            }
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->AdminUser->createManager($data)) {
                $this->success("添加用户成功！", url('admin/manager/index'));
            } else {
                $error = $this->AdminUser->getError();
                $this->error($error ? $error : '添加失败！');
            }

        } else {
            $this->assign("roles", model('admin/AuthGroup')->getGroups());
            return $this->fetch();
        }
    }

    /**
     * 管理员编辑
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post('');
            if($data['roleid'] == 1){
                $result = $this->validate($data, 'AdminUser.updateadmin');
            }else{
                $result = $this->validate($data, 'AdminUser.update');
            }
            if (true !== $result) {
                return $this->error($result);
            }
            if ($this->AdminUser->editManager($data)) {
                $this->success("修改成功！", url('admin/manager/index'));
            } else {
                $this->error($this->User->getError() ?: '修改失败！');
            }
        } else {
            $id = $this->request->param('id/d');
            $data = $this->AdminUser->where(array("userid" => $id))->find();
            if (empty($data)) {
                $this->error('该信息不存在！');
            }
            $this->assign("data", $data);
            $this->assign("roles", model('admin/AuthGroup')->getGroups());
            return $this->fetch();
        }
    }

    /**
     * 管理员删除
     */
    public function del()
    {
        $id = $this->request->param('id/d');
        if ($this->AdminUser->deleteManager($id)) {
            $this->success("删除成功！");
        } else {
            $this->error($this->AdminUser->getError() ?: '删除失败！');
        }
    }

}
