<?php

/**
 * AdminRole 表单验证器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 23:55
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\model\AdminUser as UserModel;

/**
 * Role 表单验证器
 */
class AdminRole extends Validate
{
    /**
     * @var     rule    验证规则
     */
    protected $rule = [
        'name'  => 'require|max:32'
    ];
    /**
     * @var     message     提示信息
     */
    protected $message = [
        'name.require'      => '名称不能为空',
        'name.max'          => '名称不能超过32个字符'
    ];
    /**
     * delete 验证场景
     */
    public function sceneDelete()
    {
        return $this->only(['id'])->append('id', 'checkUserIsEmpty');
    }
    /**
     * 验证 角色是否已经被用户使用
     * @param       value   需要验证的字段值
     * @param       rule    验证规则
     */
    public function checkUserIsEmpty($value, $rule)
    {
        if (UserModel::field('id')->where('admin_role_id', $value)->find()) {
            return '该角色已被用户使用';
        }
        return true;
    }
}
