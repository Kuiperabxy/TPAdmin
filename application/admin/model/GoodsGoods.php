<?php

/**
 * 商品表 模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/06 21:38
 */

namespace app\admin\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 商品表 模型
 */
class GoodsGoods extends Model
{
    // 引入软删除trait
    use SoftDelete;
    /**
     * @var deleteTime  软删除时间字段(值为Null表示未删除)
     */
    protected $deleteTime = 'delete_time';
    /**
     * 建立模型关联
     */
    public function goodsCategory()
    {
        return $this->belongsTo('GoodsCategory');
    }
}
