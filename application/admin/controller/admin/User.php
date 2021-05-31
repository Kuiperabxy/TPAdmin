<?php

/**
 * 用户 控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 12:57
 */

namespace app\admin\controller\admin;

use app\admin\controller\Common;
use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminRole as RoleModel;
use app\admin\validate\AdminUser as UserValidate;

/**
 * 用户 控制器
 */
class User extends Common
{
    /**
     * 主函数
     */
    public function index()
    {
        // 关联预载入
        $user = UserModel::with('adminRole')->field(['password', 'salt'], true)->all();
        $this->assign('user', $user);
        return $this->fetch();
    }
    /**
     * 编辑功能
     */
    public function edit()
    {
        $id = $this->request->param('id/d', 0);
        $data = ['username' => '', 'admin_role_id' => 0];
        if ($id) {
            if (!$data = UserModel::get($id)) {
                $this->error('记录不存在。');
            }
        }
        $role = RoleModel::all();
        $this->assign('data', $data);
        $this->assign('role', $role);
        $this->assign('id', $id);
        return $this->fetch();
    }
    /**
     * 接收表单  并验证
     */
    public function save()
    {
        $id = $this->request->post('id/d', 0);
        $data = [
            'username'      => $this->request->post('username/s', '', 'trim'),
            'admin_role_id' => $this->request->post('admin_role_id/d', 0),
            'password'      => $this->request->post('password/s', '')
        ];
        if ($id && $data['password'] === '') {
            unset($data['password']);
        }
        $validate = new UserValidate();
        if ($id) {
            if (!$validate->scene('update')->check(array_merge($data, ['id' => $id]))) {
                $this->error('修改失败，' . $validate->getError() . '。');
            }
            if (!$user = UserModel::get($id)) {
                $this->error('修改失败，记录不存在。');
            }
            $user->save($data);
            $this->success('修改成功。');
        }
        if (!$validate->scene('insert')->check($data)) {
            $this->error('添加失败，' . $validate->getError() . '。');
        }
        UserModel::create($data);
        $this->success('添加成功。');
    }
    /**
     * 删除功能
     */
    public function delete()
    {
        $id =$this->request->param('id/d', 0);
        if (!$user = UserModel::get($id)) {
            $this->error('删除失败，记录不存在。');
        }
        $user->delete();
        $this->success('删除成功。');
    }
}
