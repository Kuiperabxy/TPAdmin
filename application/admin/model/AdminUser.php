<?php

/**
 * 访问用户数据库
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/04/30 23:25
 */

namespace app\admin\model;

use think\Model;

/**
 * 访问用户数据库模型
 */
class AdminUser extends Model
{
    /**
     * 建立模型关联
     */
    public function adminRole()
    {
        // 一对一关联 一个用户属于一个角色
        return $this->belongsTo('AdminRole');
    }
    /**
     * 对密码进行加密
     */
    public function setPasswordAttr($value)
    {
        $salt = md5(uniqid(microtime(), true));
        $this->data('salt', $salt);
        return md5(md5($value) . $salt);
    }
    /**
     * 建立关联模型
     */
    public function adminPermission()
    {
        // hasMany() 第2个参数表示权限表的角色id 第3个参数表示用户表的角色id
        return $this->hasMany('AdminPermission', 'admin_role_id', 'admin_role_id');
    }
}
