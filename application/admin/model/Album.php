<?php

/**
 * 相册目录 数据模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 23:11
 */

namespace app\admin\model;

use app\common\library\Tree;
use think\Model;

/**
 * 相册目录 数据模型
 */
class Album extends Model
{
    public static function tree()
    {
        $data = self::order('sort', 'asc')->select()->toArray();
        return new Tree($data);
    }
}
