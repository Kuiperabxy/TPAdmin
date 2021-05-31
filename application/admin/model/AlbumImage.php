<?php

/**
 * 相册图片 路径模型
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 20:52
 */

namespace app\admin\model;

use think\Model;

/**
 * 相册图片 路径模型
 */
class AlbumImage extends Model
{
    /**
     * 建立关联模型
     */
    public function album()
    {
        return $this->belongsTo('album');
    }
}
