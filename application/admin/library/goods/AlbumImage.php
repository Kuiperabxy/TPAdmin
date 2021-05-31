<?php

/**
 * 图片处理 用于根据相册的路径自动寻找相应的类来进行图像处理
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 21:49
 */

namespace app\admin\library\goods;

use think\Image;

/**
 * 图片处理 用于根据相册的路径自动寻找相应的类来进行图像处理
 */
class AlbumImage
{
    public function thumbCategoryImage($filePath, $savePath)
    {
        $image = Image::open($filePath);
        return $image->thumb(140, 140, Image::THUMB_FILLED)->save($savePath);
    }
    public function thumbGoodsImage($filePath, $savePath)
    {
        $image = Image::open($filePath);
        return $image->thumb(200, 200, Image::THUMB_FILLED)->save($savePath);
    }
    public function thumbGoodsAlbum($filePath, $savePath)
    {
        $image = Image::open($filePath);
        return $image->thumb(800, 800, Image::THUMB_FILLED)->save($savePath);
    }
}
