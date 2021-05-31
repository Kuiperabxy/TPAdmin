<?php

/**
 * 相关验证、授权功能
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/04/30 23:22
 */

namespace app\admin\library;

use app\admin\model\AdminUser as UserModel;
use app\admin\model\AdminMenu as MenuModel;
use think\facade\Session;

/**
 * 相关验证、授权功能类
 */
class Auth
{
    /**
     * @var   instance    保存自身实例
     */
    protected static $instance;
    /**
     * @var   error   验证失败时的错误信息
     */
    protected $error;
    /**
     * @var   sessionName     会话名称
     */
    protected $sessionName = 'admin';
    /**
     * @var     loginUser   当前登录用户的信息
     */
    protected $loginUser;

    /**
     * 提供自身实例
     * @param   options     本类构造函数参数
     */
    public static function getInstance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }

    /**
     * 判断用户登录的用户名、密码是否正确
     * @param   username    用户名
     * @param   password    密码
     */
    public function login($username, $password)
    {
        $user = UserModel::get(['username' => $username]);

        if (!$user) {
            $this->setError('用户不存在');
            return false;
        }

        if ($user->password != $this->passwordMD5($password, $user->salt)) {
            $this->setError('用户名或密码不正确');
            return false;
        }

        //　执行到此处　说明用户名和密码正确,登录成功

        // 保存登录状态
        Session::set($this->sessionName, ['id' => $user->id]);
        return true;
    }

    /**
     * 保存验证失败时的错误信息
     * @param   error   验证失败时的错误信息
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * 获取验证失败时的错误信息
     * @param   error   验证失败时的错误信息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 加密函数(与创建用户时的加密方式一样)
     * @param   password    密码
     * @param   salt        加密密钥
     */
    public function passwordMD5($password, $salt)
    {
        return md5(md5($password) . $salt);
    }
    /**
     * 判断Session中是否保存了用户id
     */
    public function isLogin()
    {
        // && 后面的部分 避免用户在已登录状态被删除后，由于会话没有过期，出现无效用户的情况
        return Session::has($this->sessionName . '.id') && $this->getLoginUser();
    }
    /**
     * 用户退出
     */
    public function logout()
    {
        // 删除Session中保存的用户登录信息
        Session::delete($this->sessionName);
        return true;
    }
    /**
     * 获取菜单数据
     * @param   controller      当前控制器名称
     */
    public function menu($controller)
    {
        // 当前登录用户的信息
        $user = $this->getLoginUser();
        // Menu实例
        $menu = MenuModel::tree();
        // 全部菜单信息
        $data = $menu->getData();
        // 当前登录用户可以访问的菜单信息
        $result = [];
        foreach ($user['admin_permission'] as $v) {
            if ($v['controller'] === '*') {
                $result = $data;
                break;
            }
            foreach ($data as $vv) {
                if (strtolower($v['controller']) === strtolower($vv['controller'])) {
                    $result[] = $vv;
                    break;
                }
            }
        }
        return $menu->data($result)->getTree(strtolower($controller));
    }
    /**
     * 修改当前登录用户的密码
     */
    public function changePassword($password)
    {
        $id = Session::get($this->sessionName . '.id');
        UserModel::get($id)->save(['password' => $password]);
    }
    /**
     * 获取当前登录用户的信息
     */
    public function getLoginUser($field = null)
    {
        if (!$this->loginUser) {
            $id = Session::get($this->sessionName . '.id');
            $this->loginUser = UserModel::with('adminPermission')->get($id);
        }
        return $field ? $this->loginUser[$field] : $this->loginUser;
    }
    /**
     * 权限检查
     * @param   controller      当前控制器
     * @param   action          当前操作
     */
    public function checkAuth($controller, $action)
    {
        $user = $this->getLoginUser();
        foreach ($user['admin_permission'] as $v) {
            if ($v['controller'] === '*') {
                return true;
            }
            if (strtolower($v['controller']) === strtolower($controller)) {
                if ($v['action'] === '*') {
                    return true;
                }
                if (in_array($action, explode(',', $v['action']))) {
                    return true;
                }
            }
        }
        return false;
    }
}
