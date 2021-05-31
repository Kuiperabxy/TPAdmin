<?php

/**
 * 角色管理 数据库模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 17:39
 */

namespace app\admin\model;

use think\Model;

/**
 * 角色管理 数据库模型
 */
class AdminRole extends Model
{
    /**
     * 建立模型关联
     */
    public function adminPermission()
    {
        return $this->hasMany('AdminPermission');
    }
}
