<?php

/**
 * 处理树形结构的类(公共)
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/03 11:34
 */

namespace app\common\library;

/**
 * 处理树形结构的类, 如菜单的树形结构
 */
class Tree
{
    /**
     * @var   idName      元素id名称
     */
    protected $idName = 'id';
    /**
     * @var   pidName     父id(上一级id)名称
     */
    protected $pidName = 'pid';
    /**
     * @var   subName     子id(下一级id)名称
     */
    protected $subName = 'sub';
    /**
     * @var   levelName     菜单项的层级名称
     */
    protected $levelName = 'level';
    /**
     * @var   data        查询得到的数据
     */
    protected $data = [];

    /**
     * 构造函数
     * @param   data    查询得到的数据
     */
    public function __construct(array $data = [])
    {
        $this->data($data);
    }
    /**
     * 数据处理
     * @param   data    查询得到的数据
     */
    public function data(array $data = [])
    {
        $this->data = $data;
        return $this;
    }
    /**
     * 获取数据
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * 获取整理后的菜单数组
     */
    public function getTree()
    {
        return $this->tree($this->data);
    }
    /**
     * 通过递归处理将数据库查询到的二维数据转换为多层嵌套的数组
     * @param   data        查询得到的数据
     * @param   pid         父id
     * @var     sub         子元素数组
     * @return  result      处理后的数据
     */
    public function tree($data, $pid = 0)
    {
        $result = [];
        // 遍历数据数组
        foreach ($data as $v) {
            // 判断子菜单数组中的 pid的值
            if ($v[$this->pidName] === $pid) {
                // 递归处理
                $sub = $this->tree($data, $v[$this->idName]);
                $v[$this->subName] = $sub;
                $result[] = $v;
            }
        }
        return $result;
    }
    /**
     * 递归整理菜单项数组，记录每一项的层级数
     * @param   data        查询得到的数据
     * @param   pid         父id
     * @param   level       菜单项所在的层级数
     * @param   tree        处理后得到的数据数组
     * @return  tree        处理后得到的数据数组
     */
    public function treeList($data, $pid = 0, $level = 0, &$tree = [])
    {
        // 遍历数组
        foreach ($data as $v) {
            // 判断子菜单数组中的 pid的值
            if ($v[$this->pidName] === $pid) {
                // 保存菜单项的层级数 一级菜单项的层级为0, 二级菜单项的层级数为1
                $v[$this->levelName] = $level;
                $tree[] = $v;
                // 递归处理
                $this->treeList($data, $v['id'], $level + 1, $tree);
            }
        }
        return $tree;
    }
    /**
     * 获取递归整理后的菜单项数组
     */
    public function getTreeList()
    {
        return $this->treeList($this->data);
    }
    /**
     * 判断每个分类项是否为叶子节点
     */
    public function getTreeListCheckLeaf($name = 'isLeaf')
    {
        $data = $this->getTreeList();
        foreach ($data as $k => $v) {
            foreach ($data as $vv) {
                $data[$k][$name] = true;
                if ($v[$this->idName] === $vv[$this->pidName]) {
                    $data[$k][$name] = false;
                    break;
                }
            }
        }
        return $data;
    }
}
