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
// | 后台菜单模型
// +----------------------------------------------------------------------
namespace app\admin\model;

use \think\Db;
use \think\Model;

class Vb extends Model
{

    /**
     * 获取列表
     * @return type
     */
    final public function getList($where)
    {
        if(!is_array($where)){
            $where = array();
        }

        $result = $this->where($where)->select()->toArray();
        return $result;
    }

    /**
     * 添加后台菜单
     */
    public function add($data)
    {
        $validate = new \app\admin\validate\Menu;
        $result = $validate->scene('add')->check($data);
        if (!$result) {
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->save($data) !== false ? true : false;
    }

    /**
     * 修改后台菜单
     */
    public function edit($data)
    {
        $validate = new \app\admin\validate\Menu;
        $result = $validate->scene('edit')->check($data);
        if (!$result) {
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->isUpdate(true)->save($data) !== false ? true : false;
    }

    /**
     * 删除菜单
     */
    public function del($id)
    {
        $result = $this->where(['id' => $id])->delete();
        if ($result) {
            return true;
        } else {
            $this->error = "删除失败";
            return false;
        }
    }

}
