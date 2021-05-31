<?php

/**
 * 商品栏目 控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/05 16:58
 */

namespace app\admin\controller\goods;

use app\admin\model\GoodsCategory as CategoryModel;
use app\admin\validate\GoodsCategory as CategoryValidate;
use app\admin\controller\Common;
use app\admin\model\GoodsGoods as GoodsModel;

/**
 * 商品栏目 控制器
 */
class Category extends Common
{
    /**
     * 主函数
     */
    public function index()
    {
        $category = CategoryModel::tree()->getTreeListCheckLeaf();
        $this->assign('category', $category);
        return $this->fetch();
    }
    /**
     * 排序功能
     */
    public function sort()
    {
        $sort = $this->request->post('sort/a', []);
        $data = [];
        foreach ($sort as $k => $v) {
            $data[] = ['id' => (int)$k, 'sort' => (int)$v];
        }
        $menu = new CategoryModel();
        $menu->saveAll($data);
        $this->success('改变排序成功。');
    }
    /**
     * 编辑功能
     */
    public function edit()
    {
        $id = $this->request->param('id/d', 0);
        $data = ['pid' => 0, 'name' => '', 'image' => '', 'sort' => 0];
        if ($id) {
            if (!$data = CategoryModel::get($id)) {
                $this->error('记录不存在。');
            }
        }
        $category = CategoryModel::tree()->getTreeList();
        $this->assign('category', $category);
        $this->assign('data', $data);
        $this->assign('album_id', 2);
        $this->assign('id', $id);
        return $this->fetch();
    }
    /**
     * 接收表单 并验证
     */
    public function save()
    {
        $id = $this->request->post('id/d', 0);
        $data = [
            'sort' => $this->request->post('sort/d', 0),
            'pid' => $this->request->post('pid/d', 0),
            'name' => $this->request->post('name/s', '', 'trim'),
            'image' => $this->request->post('image/s', '', 'trim')
        ];
        $validate = new CategoryValidate();
        if ($id) {
            if (!$validate->scene('update')->check(array_merge($data, ['id' => $id]))) {
                $this->error('修改失败，' . $validate->getError() . '。');
            }
            if (!$menu = CategoryModel::get($id)) {
                $this->error('修改失败，记录不存在。');
            }
            $menu->save($data);
            $this->success('修改成功。');
        }
        if (!$validate->scene('insert')->check($data)) {
            $this->error('添加失败，' . $validate->getError() . '。');
        }
        CategoryModel::create($data);
        $this->success('添加成功。');
    }
    /**
     * 删除功能
     */
    public function delete()
    {
        $id = $this->request->param('id/d', 0);
        $validate = new CategoryValidate();
        if (!$validate->scene('delete')->check(['id' => $id])) {
            $this->error('删除失败，' . $validate->getError() . '。');
        }
        if (!$category = CategoryModel::get($id)) {
            $this->error('删除失败，记录不存在。');
        }

        // 在删除分类时,将改分类下的商品的分类id设为0
        GoodsModel::withTrashed()->where('goods_category_id', $id)->update(['goods_category_id' => 0]);
        $category->delete();
        $this->success('删除成功。');
    }
}
