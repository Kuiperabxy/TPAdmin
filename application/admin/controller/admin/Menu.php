<?php

/**
 * 菜单 控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 14:12
 */

namespace app\admin\controller\admin;

use app\admin\model\AdminMenu as MenuModel;
use app\admin\controller\Common;
use app\admin\validate\AdminMenu as MenuValidate;

/**
 * 菜单 控制器
 */
class Menu extends Common
{
    /**
     * 主函数
     * @var     menu    菜单项数组
     */
    public function index()
    {
        $menu = MenuModel::tree()->getTreeList();
        $this->assign('menu', $menu);
        return $this->fetch();
    }
    /**
     * 保存菜单的排序值
     */
    public function sort()
    {
        // 通过post()方法获取排序的数据, 一个数组
        $sort = $this->request->post('sort/a', []);
        $data = [];
        foreach ($sort as $k => $v) {
            $data[] = ['id' => (int)$k, 'sort' => (int)$v];
        }
        $menu = new MenuModel;
        $menu->saveAll($data);
        $this->success('改变排序成功。');
    }
    /**
     * 菜单添加与修改
     */
    public function edit()
    {
        $id = $this->request->param('id/d', 0);
        // 空数组  !$data 为 bool(false)
        $data = [
            'pid'           => 0,
            'name'          => '',
            'icon'          => '',
            'controller'    => '',
            'sort'          => 0
        ];
        // 当$id 为0时 当前操作为新增 | 当$id 不为0时 当前操作为 编辑
        if ($id) {
            if (!$data = MenuModel::get($id)) {
                $this->error('记录不存在。');
            }
        }
        $menu = MenuModel::tree()->getTreeList();
        $this->assign('menu', $menu);
        $this->assign('data', $data);
        $this->assign('id', $id);
        return $this->fetch();
    }
    /**
     * 接收表单数据
     */
    public function save()
    {
        $id = $this->request->post('id/d', 0);
        $data = [
            'pid'           => $this->request->post('pid/d', 0),
            'sort'          => $this->request->post('sort/d', 0),
            'name'          => $this->request->post('name/s', '', 'trim'),
            'icon'          => $this->request->post('icon/s', '', 'trim'),
            'controller'    => $this->request->post('controller/s', '', 'trim')
        ];
        // 使用 MenuValidate表单验证器 进行验证
        $validate = new MenuValidate;
        // 修改
        if ($id) {
            if (!$validate->scene('update')->check(array_merge($data, ['id' => $id]))) {
                $this->error('修改失败，'.$validate->getError().'。');
            }

            if (!$menu = MenuModel::get($id)) {
                $this->error('修改失败，记录不存在。');
            }
            $menu->save($data);
            $this->success('修改成功。');
        }
        // 添加
        if (!$validate->scene('insert')->check($data)) {
            $this->error('添加失败，'.$validate->getError().'。');
        }
        MenuModel::create($data);
        $this->success('添加成功。');
    }
    /**
     * 删除数据功能
     */
    public function delete()
    {
        $id = $this->request->param('id/d', 0);
        $validate = new MenuValidate;
        if (!$validate->scene('delete')->check(['id' => $id])) {
            $this->error('删除失败，'.$validate->getError().'。');
        }
        if (!$menu = MenuModel::get($id)) {
            $this->error('删除失败，记录不存在。');
        }
        $menu->delete();
        $this->success('删除成功。');
    }
}
