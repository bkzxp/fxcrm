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
// | 后台用户管理
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\helper\Hash;
use think\Model;

class AdminUser extends Model
{
    //超级管理员角色id
    const administratorRoleId = 1;

    // 设置当前模型对应的完整数据表名称
    protected $table = '__ADMIN__';
    protected $pk = 'userid';
    protected $insert = ['status' => 1];

    /**
     * 创建管理员
     * @param type $data
     * @return boolean
     */
    public function createManager($data)
    {
        if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
        $passwordinfo = encrypt_password($data['password']); //对密码进行处理
        $data['password'] = $passwordinfo['password'];
        $data['encrypt'] = $passwordinfo['encrypt'];
        $id = $this->allowField(true)->save($data);
        if ($id) {
            return $id;
        }
        $this->error = '入库失败！';
        return false;
    }

    /**
     * 编辑管理员
     * @param [type] $data [修改数据]
     * @return boolean
     */
    public function editManager($data)
    {
        if (empty($data) || !isset($data['userid']) || !is_array($data)) {
            $this->error = '没有修改的数据！';
            return false;
        }
        $info = $this->where(array('userid' => $data['userid']))->find();
        if (empty($info)) {
            $this->error = '该管理员不存在！';
            return false;
        }
        //密码为空，表示不修改密码
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
            unset($data['encrypt']);
        } else {
            $passwordinfo = encrypt_password($data['password']); //对密码进行处理
            $data['encrypt'] = $passwordinfo['encrypt'];
            $data['password'] = $passwordinfo['password'];
        }
        $status = $this->allowField(true)->isUpdate(true)->save($data);
        return $status !== false ? true : false;
    }

    /**
     * 删除管理员
     * @param type $userId
     * @return boolean
     */
    public function deleteManager($userId)
    {
        $userId = (int) $userId;
        if (empty($userId)) {
            $this->error = '请指定需要删除的用户ID！';
            return false;
        }
        if ($userId == 1) {
            $this->error = '禁止对超级管理员执行该操作！';
            return false;
        }
        if (false !== $this->where(array('userid' => $userId))->setField('status', 0)) {
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }

    /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool|mixed
     */
    public function login($username = '', $password = '')
    {
        $username = trim($username);
        $password = trim($password);
        $map['username'] = $username;
        $userInfo = self::get($map);
        if (!$userInfo) {
            $this->error = '用户不存在！';
        } elseif (!$userInfo['status']) {
            $this->error = '用户已被禁用！';
        } else {
            //密码判断
            if (!empty($password) && encrypt_password($password, $userInfo['encrypt']) != $userInfo['password']) {
                $this->error = '密码错误！';
            } else {
                $this->autoLogin($userInfo);
                return true;
            }
        }
        return false;
    }

    /**
     * 自动登录用户
     */
    public function autoLogin($userInfo)
    {
        //记录行为
        //action_log('user_login', 'member', $userInfo['userid'], $userInfo['userid']);
        /* 更新登录信息 */
        $data = array(
            'uid' => $userInfo['userid'],
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(1),
        );
        $this->loginStatus((int) $userInfo['userid']);
        /* 记录登录SESSION和COOKIES */
        $auth = [
            'uid' => $userInfo['userid'],
            'username' => $userInfo['username'],
            'last_login_time' => $userInfo['last_login_time'],
        ];
        Session('admin_user_auth', $auth);
        Session('admin_user_auth_sign', data_auth_sign($auth));
    }

    /**
     * 更新登录状态信息
     * @param type $userId
     * @return type
     */
    public function loginStatus($userId)
    {
        $data = ['last_login_time' => time(), 'last_login_ip' => get_client_ip(1)];
        return $this->save($data, ['userid' => $userId]);
    }

    /**
     * 获取用户信息
     * @param type $identifier 用户名或者用户ID
     * @return boolean|array
     */
    public function getUserInfo($identifier, $password = null)
    {
        if (empty($identifier)) {
            return false;
        }
        $map = array();
        //判断是uid还是用户名
        if (is_int($identifier)) {
            $map['userid'] = $identifier;
        } else {
            $map['username'] = $identifier;
        }
        $userInfo = $this->where($map)->find()->toArray();
        if (empty($userInfo)) {
            return false;
        }
        //密码验证
        if (!empty($password) && password($password, $userInfo['encrypt']) != $userInfo['password']) {
            return false;
        }
        return $userInfo;
    }

    /**
     * 检验用户是否已经登陆
     */
    public function isLogin()
    {
        $user = session('admin_user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return session('admin_user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
        }
    }

    /**
     * 检查当前用户是否超级管理员
     * @return boolean
     */
    public function isAdministrator()
    {
        $userInfo = $this->getUserInfo($this->isLogin());
        if (!empty($userInfo) && $userInfo['roleid'] == self::administratorRoleId) {
            return true;
        }
        return false;
    }

    /**
     * 注销登录状态
     * @return boolean
     */
    public function logout()
    {
        session(null);
        return true;
    }

}
