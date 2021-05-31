<?php

/**
 * 商品栏目 模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 16:54
 */

namespace app\admin\model;

use app\common\library\Tree;
use think\Model;

/**
 * 商品栏目 数据模型
 */
class GoodsCategory extends Model
{
    /**
     * 获取栏目树
     */
    public static function tree()
    {
        $model = new self;
        $data = $model->order('sort', 'asc')->select()->toArray();
        return new Tree($data);
    }
}
