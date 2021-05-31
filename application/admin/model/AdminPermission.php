<?php

/**
 * 权限 数据模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 10:53
 */

namespace app\admin\model;

use think\Model;

/**
 * 权限 数据模型
 */
class AdminPermission extends Model
{
    /**
     * 修改器 规范action字段的值
     */
    public function setActionAttr($value)
    {
        return implode(',', array_map('trim', explode(',', $value)));
    }
}
