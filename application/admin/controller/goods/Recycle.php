<?php

/**
 * 回收站 控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/06 22:18
 */

namespace app\admin\controller\goods;

use app\admin\model\GoodsGoods as GoodsModel;
use app\admin\controller\Common;

/**
 * 回收站 控制器
 */
class Recycle extends Common
{
    /**
     * 主函数
     */
    public function index()
    {
        // 只查询已被软删除的记录
        $goods = GoodsModel::onlyTrashed()->with('goodsCategory')->field('content,album', true)->order('id', 'desc');
        $params = [];
        $goods = $goods->paginate(15, false, ['type' => 'bootstrap', 'var_page' => 'page', 'query' => $params]);

        $this->assign('goods', $goods);
        return $this->fetch();
    }
    /**
     * 商品恢复
     */
    public function restore()
    {
        $id = $this->request->param('id/d', 0);
        if (!$goods = GoodsModel::onlyTrashed()->find($id)) {
            $this->error('记录不存在。');
        }
        $goods->restore();
        $this->success('恢复成功。');
    }
    /**
     * 从回收站删除
     */
    public function delete()
    {
        $id = $this->request->param('id/d', 0);
        if (!$goods = GoodsModel::onlyTrashed()->get($id)) {
            $this->error('删除失败，记录不存在。');
        }
        $goods->delete(true);
        $this->success('删除成功。');
    }
}
