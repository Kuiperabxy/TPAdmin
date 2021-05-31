<?php

/**
 * 菜单数据库模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 12:40
 */

namespace app\admin\model;

use think\Model;
use app\admin\library\Menu;

/**
 * 查询菜单数据模型
 */
class AdminMenu extends Model
{
    /**
     * 查询菜单数据
     * @var     data    查询得到的数据数组
     * @return  Menu类的实例
     */
    public static function tree()
    {
        $data = self::order('sort', 'asc')->select()->toArray();
        return new Menu($data);
    }
}
