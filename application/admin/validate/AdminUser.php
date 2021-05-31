<?php

/**
 * AdminUser 表单验证器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/04/30 22:32
 */

namespace app\admin\validate;

use think\Validate;
use app\admin\model\AdminRole as RoleModel;
use app\admin\model\AdminUser as UserModel;

/**
 * AdminUser 表单验证器
 */
class AdminUser extends Validate
{
    // 验证规则(默认)
    protected $rule = [
        'username'  => 'require|max:32',
        'password'  => 'require|min:6'
    ];
    // 规则错误对应的提示
    protected $message = [
        'username.require'  => '用户名不能为空',
        'username.max'      => '用户名最多为32个字符',
        'password.require'  => '密码不能为空',
        'password.min'      => '密码最少为6位',
        'captcha.require'   => '验证码不能为空',
        'captcha.captcha'   => '验证码有误',
        'username.unique'   => '用户名已存在'
    ];

    /**
     * 登录场景的验证规则
     */
    public function sceneLogin()
    {
        return $this->append('captcha', 'require|captcha');
    }
    /**
     * 插入 验证场景
     */
    public function sceneInsert()
    {
        return $this->append('admin_role_id', 'checkAdminRoleId')
         ->append('username', 'unique:admin_user, username');
    }
    /**
     * 更新 验证场景
     */
    public function sceneUpdate()
    {
        return $this->append('admin_role_id', 'checkAdminRoleId')
        ->remove('password', 'require')
        ->append('username', 'unique:admin_user, username');
    }
    /**
     * 验证方法
     */
    public function checkAdminRoleId($value, $rule)
    {
        if (!RoleModel::field('id')->get($value)) {
            return '角色不存在';
        }
        return true;
    }
}
