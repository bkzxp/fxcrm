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
// | 后台菜单管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Menu as Menu_Model;
use app\common\controller\Adminbase;
use think\Db;

class Menu extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->Menu = new Menu_Model;
    }

    //后台菜单首页
    public function index()
    {
        $tree = new \util\Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
        $array = array();
        foreach ($result as $r) {
            $r['str_manage'] = '<a class="layui-btn layui-btn-xs" href=' . url("Menu/edit", array("id" => $r['id'])) . '>编辑</a><a class="layui-btn layui-btn-xs layui-btn-normal" href=' . url("Menu/add", array("parentid" => $r['id'])) . '>添加</a><a class="layui-btn layui-btn-xs ajax-get confirm layui-btn-danger" url=' . url("Menu/delete", array("id" => $r['id'])) . '>删除</a>';
            $r['status'] = $r['status'] ? "<span class='on'><i class='icon iconfont icon-xianshi'></i>显示</span>" : "<span class='off'><i class='icon iconfont icon-yincang'></i>隐藏</span>";
            $array[] = $r;
        }
        $str = "<tr>
        <td>\$listorder</td>
        <td>\$id</td>
        <td>\$str_manage</td>
        <td>\$spacer\$title</td>
        <td>\$status</td>
        </tr>";
        $tree->init($array);
        $categorys = $tree->get_tree(0, $str);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    //添加后台菜单
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            if ($this->Menu->add($data)) {
                $this->success("添加成功！", url("Menu/index"));
            } else {
                $error = $this->Menu->getError();
                $this->error($error ? $error : '添加失败！');
            }
        } else {
            $tree = new \util\Tree();
            $parentid = $this->request->param('parentid/d', '');
            $result = Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
            $array = array();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("select_categorys", $select_categorys);
            return $this->fetch();
        }
    }

    //编辑后台菜单
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            if (!isset($data['status'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
            if ($this->Menu->edit($data)) {
                $this->success("编辑成功！", url("Menu/index"));
            } else {
                $error = $this->Menu->getError();
                $this->error($error ? $error : '编辑失败！');
            }
        } else {
            $tree = new \util\Tree();
            $id = $this->request->param('id/d', '');
            $rs = Db::name('menu')->where(["id" => $id])->find();
            $result = Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
            $array = array();
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $rs['parentid'] ? 'selected' : '';
                $array[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$title</option>";
            $tree->init($array);
            $select_categorys = $tree->get_tree(0, $str);
            $this->assign("data", $rs);
            $this->assign("select_categorys", $select_categorys);
            return $this->fetch();
        }

    }

    /**
     * 菜单删除
     */
    public function delete()
    {
        $id = $this->request->param('id/d');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $result = Db::name('menu')->where(["parentid" => $id])->find();
        if ($result) {
            $this->error("含有子菜单，无法删除！");
        }
        if ($this->Menu->del($id) !== false) {
            $this->success("删除菜单成功！");
        } else {
            $this->error("删除失败！");
        }
    }

}
