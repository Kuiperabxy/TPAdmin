<?php

/**
 * 菜单处理类
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 12:14
 */

namespace app\admin\library;

use app\common\library\Tree;

/**
 * 菜单处理类
 */
class Menu extends Tree
{
    /**
     * 获取整理后的菜单数组
     * @param   curr        当前控制器的名称
     * @var     data        查询得到的数据数组
     */
    public function getTree($curr = '')
    {
        $data = $this->data;
        foreach ($data as $k => $v) {
            $data[$k]['curr'] = $this->isCurr($v['controller'], $curr);
        }
        return $this->tree($data, 0);
    }
    /**
     * 判断菜单的控制器是否为当前控制器
     * @param   test        菜单控制器的名称
     * @param   curr        当前控制器的名称
     */
    public function isCurr($test, $curr)
    {
        return ($test === $curr) || ($test . '.' === substr($curr, 0, strlen($test) + 1));
    }
}
