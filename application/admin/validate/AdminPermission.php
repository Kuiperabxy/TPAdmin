<?php

/**
 * 角色权限 验证器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 12:02
 */

namespace app\admin\validate;

use app\admin\model\AdminRole as RoleModel;
use think\Validate;

/**
 * 角色权限 验证器
 */
class AdminPermission extends Validate
{
    // 验证规则
    protected $rule = [
        'controller' => 'require|max:32',
        'action'     => 'require|max:255',
    ];
    // 提示信息
    protected $message = [
        'controller.require' => '控制器不能为空',
        'action.require'     => '操作不能为空'
    ];
    // 插入 验证场景
    public function sceneInsert()
    {
        return $this->append('admin_role_id', 'checkAdminRoleId');
    }
    // 更新 验证场景
    public function sceneUpdate()
    {
        return $this->append('admin_role_id', 'checkAdminRoleId');
    }
    // 验证方法
    public function checkAdminRoleId($value, $rule)
    {
        if (!RoleModel::field('id')->get($value)) {
            return '角色不存在';
        }
        return true;
    }
}
