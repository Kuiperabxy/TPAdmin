<?php

/**
 * AdminMenu 表单 验证器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 16:13
 */

namespace app\admin\validate;

use app\admin\model\AdminMenu as MenuModel;
use think\Validate;

/**
 * AdminMenu 表单验证器
 */
class AdminMenu extends Validate
{
    // 验证规则
    protected $rule = [
        'name'          => 'require|max:32',
        'icon'          => 'require|max:32',
        'controller'    => 'require|max:32',
        // number 规则验证某个字段是否为纯数字, 不包含负数和小数点
        'sort'          => 'number',
        'pid'           => 'number'
    ];
    // 提示信息
    protected $message = [
        'name.require'              => '名称不能为空',
        'name.max'                  => '名称不能超过32个字符',
        'icon.require'              => '图标不能为空',
        'icon.max'                  => '图标不能超过32个字符',
        'controller.require'        => '控制器不能为空',
        'controller.max'            => '控制器不能超过32个字符',
        'sort.number'               => '排序值必须是数字',
        // 验证规则 different 用来验证某个字段是否和另外一个字段的值不一致
        'pid.different'             => '不能选择自己作为上级菜单'
    ];
    /**
     * Insert 验证场景
     * @return  Validate类对象
     */
    public function sceneInsert()
    {
        return $this->append('pid', 'checkPidIsTop');
    }
    /**
     * Update 验证场景
     * @return  Validate类对象
     */
    public function sceneUpdate()
    {
        return $this->append('pid', 'checkPidIsTop')->append('pid', 'different:id');
    }
    /**
     * Delete 验证场景
     */
    public function sceneDelete()
    {
        return $this->only(['id'])->append('id', 'checkIdIsLeaf');
    }
    /**
     * 验证id 和 pid是否不一致
     * @param   value   需要验证的值
     * @param   rule    验证的规则
     */
    public function checkPidIsTop($value, $rule)
    {
        if ($value !== 0) {
            if (!$data = MenuModel::field('pid')->get($value)) {
                return '上级菜单不存在';
            }
            if ($data->pid) {
                return '上级菜单不能使用子项';
            }
        }
        return true;
    }
    /**
     * 验证给定项是否存在子项
     * @param   value   需要验证的值
     * @param   rule    验证的规则
     */
    public function checkIdIsLeaf($value, $rule)
    {
        $data = MenuModel::field('id')->where('pid', $value)->find();
        return $data ? '存在子项' : true;
    }
}
