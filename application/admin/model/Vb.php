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
    final public function getList($where, $limit)
    {
        if(!is_array($where)){
            $where = array();
        }

        $total = $this->where($where)->sum('vb');
        $count = $this->where($where)->count();
        $list = $this->where($where)->order(array('create_time' => 'DESC'))->limit($limit)->select()->toArray();

        return array('data'=>$list, 'count'=>$count, 'total'=>$total);
    }

    //获取代理商列表
    final public function getAgents(){
        $agents = Db::name("admin")->where('roleid','eq',4)->where('status','>',0)->order(array('userno' => 'ASC'))->select();
        return $agents;
    }

    /**
     * 添加VB
     * @param type $data
     * @return boolean
     */
    final public function createVb($post)
    {
        if (empty($post)) {
            $this->error = '没有数据！';
            return false;
        }
        $data['userno']=$post['userno'];
        $data['vb'] = $post['vb'];
        
        $agents = Db::name("admin")->where('userno','eq',$data['userno'])->limit(1)->find();
        $data['agent_name'] = $agents['nickname'];
        $data['uid'] = $agents['userid'];
        $data['create_time'] = time();
        $data['source'] = $post['source'];
      
        $id = $this->allowField(true)->save($data);
        if ($id) {
            return $id;
        }
        $this->error = '添加VB失败！';
        return false;
    }
    
    /**
     * 编辑Vb
     * @param [type] $data [修改数据]
     * @return boolean
     */
    final public function editVb($post)
    {
        if (empty($post) || !isset($post['id']) || !is_array($post)) {
            $this->error = '没有修改的数据！';
            return false;
        }
        $info = $this->where(array('id' => $post['id']))->find();
       
        if (empty($info)) {
            $this->error = '该信息不存在！';
            return false;
        }
        $data['id']=$post['id'];
        $data['userno']=$post['userno'];
        $data['vb'] = $post['vb'];
        
        $agents = Db::name("admin")->where('userno','eq',$data['userno'])->limit(1)->find();
        $data['agent_name'] = $agents['nickname'];
        $data['uid'] = $agents['userid'];
        $data['create_time'] = time();
        $data['source'] = $post['source'];
        
        $status = $this->allowField(true)->isUpdate(true)->save($data);
        return $status !== false ? true : false;
    }

}
