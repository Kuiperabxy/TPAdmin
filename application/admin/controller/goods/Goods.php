<?php

/**
 * 商品表 控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/06 21:37
 */

namespace app\admin\controller\goods;

use app\admin\controller\Common;
use app\admin\model\GoodsCategory as CategoryModel;
use app\admin\model\GoodsGoods as GoodsModel;
use app\admin\validate\GoodsGoods as GoodsValidate;
use think\facade\Config;

/**
 * 商品表 控制器
 */
class Goods extends Common
{
    /**
     * 主函数
     */
    public function index()
    {
        // 获取相关参数
        $category_id = $this->request->param('category_id/d', 0);
        $search = $this->request->get('search/s', '');
        $pagesize = $this->request->get('pagesize/d', 15);
        $goods = GoodsModel::with('goodsCategory')->field('content,album', true)->order('id', 'desc');
        // 判断
        if ($category_id) {
            $goods->where('goods_category_id', $category_id);
        }
        // 搜索功能
        if ($search !== '') {
            // 对提交的搜索关键词进行转义处理
            $sql_search = strtr($search, ['%' => '\&', '_' => '\_', '\\' => '\\\\']);
            $goods->whereLike('name', '%' . $sql_search . '%');
        }
        $params = ['search' => $search, 'pagesize' => $pagesize];
        $goods = $goods->paginate($pagesize, false, ['type' => 'bootstrap', 'var_page' => 'page', 'query' => $params]);
        $category = CategoryModel::tree()->getTree();

        $this->assign('goods', $goods);
        $this->assign('category', $category);
        $this->assign('category_id', $category_id);
        $this->assign('search', $search);
        return $this->fetch();
    }
    /**
     * 软删除
     */
    public function delete()
    {
        $id = $this->request->param('id/d', 0);
        if (!$goods = GoodsModel::get($id)) {
            $this->error('删除失败，记录不存在。');
        }
        $goods->delete();
        $this->success('删除成功。');
    }
    /**
     * 上架 下架功能
     */
    public function changeStatus()
    {
        // 获取参数
        $id = $this->request->param('id/d', 0);
        $status = $this->request->param('status/d', 0);
        $validate = new GoodsValidate();

        if (!$validate->scene('changeStatus')->check(['status' => $status])) {
            $this->error('操作失败，' . $validate->getError() . '。');
        }
        if (!$goods = GoodsModel::get($id)) {
            $this->error('记录不存在。');
        }
        $goods->save(['status' => $status]);
        $this->success(($status ? '上架' : '下架') . '成功。');
    }
    /**
     * 商品添加与修改
     */
    public function edit()
    {
        // 获取参数
        $id = $this->request->param('id/d', 0);
        $category_id = $this->request->param('category_id/d', 0);
        $data = [
            'goods_category_id'     => $category_id,
            'name'                  => '',
            'sell_point'            => '',
            'price'                 => 0,
            'num'                   => 0,
            'image'                 => '',
            'status'                => 0,
            'content'               => '',
            'album'                 => ''
        ];

        if ($id) {
            if (!$data = GoodsModel::get($id)) {
                $this->error('记录不存在。');
            }
        }
        $data['album'] = $data['album'] ? explode('|', $data['album']) : [];
        $category = CategoryModel::tree()->getTree();

        $this->assign('category', $category);
        $this->assign('data', $data);
        $this->assign('id', $id);
        // 将相册id 传给页面
        $config = Config::get('tpadmin.goods');
        $this->assign('album_image_id', $config['album_image_id']);
        $this->assign('album_album_id', $config['album_album_id']);
        $this->assign('album_editor_id', $config['album_editor_id']);
        return $this->fetch();
    }
    /**
     * 接收表单 并验证
     */
    public function save()
    {
        $id = $this->request->post('id/d', 0);
        $data = [
            'goods_category_id' => $this->request->post('goods_category_id/d', 0),
            'name' => $this->request->post('name/s', '', 'trim'),
            'sell_point' => $this->request->post('sell_point/s', '', 'trim'),
            'price' => $this->request->post('price/f', 0),
            'num' => $this->request->post('num/d', 0),
            'image' => $this->request->post('image/s', '', 'trim'),
            'status' => $this->request->post('status/d', 0),
            'content' => $this->request->post('content/s', ''),
            'album' => implode('|', $this->request->post('album/a', [], 'trim')),
        ];
        $validate = new GoodsValidate();
        if ($id) {
            if (!$validate->scene('update')->check($data)) {
                $this->error('修改失败，' . $validate->getError() . '。');
            }
            if (!$goods = GoodsModel::get($id)) {
                $this->error('修改失败，记录不存在。');
            }
            $goods->save($data);
            $this->success('修改成功。');
        }
        if (!$validate->scene('insert')->check($data)) {
            $this->error('添加失败，' . $validate->getError() . '。');
        }
        GoodsModel::create($data);
        $this->success('添加成功。');
    }
}
