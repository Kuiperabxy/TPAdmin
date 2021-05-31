<?php

/**
 * 公共控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/04/30 23:59
 */

namespace app\admin\controller;

use app\admin\library\Auth;
use think\Controller;
use think\facade\Session;

// use think\facade\Validate;   基于一次的令牌验证

/**
 * 公共控制器,基于所有功能控制器之上
 */
class Common extends Controller
{
    /**
     * @param   auth    保存Auth类的实例
     */
    protected $auth;
    /**
     * @param   checkLoginExclude   排除列表, 表示定义在该数组中的方法名将不需要检测用户是否登录
     */
    protected $checkLoginExclude = [];
    /**
     * 初始化
     */
    public function initialize()
    {
        // 判断当前是否为POST请求
        if ($this->request->isPost()) {
            /* 基于一次性的令牌验证
            // 从请求头中取出令牌
            $token = ['X-CSRF-TOKEN' => $this->request->header('X-CSRF-TOKEN')];
            // 令牌验证
            if (!Validate::token(null, 'X-CSRF-TOKEN', $token)) {
                // 生成新的令牌
                $this->request->token('X-CSRF-TOKEN');
                $this->error('令牌已过期，请重新提交。');
            }
            $token = $this->request->token('X-CSRF-TOKEN');
            */

            /**
             * 在一段时间内有效地令牌验证
             */
            // 获取令牌
            $token = $this->getToken();
            // 将令牌放入响应头中发送
            header('X-CSRF-TOKEN：' . $token);
            // 判断请求头中的令牌和Session保存的令牌是否一致
            if ($token !== $this->request->header('X-CSRF-TOKEN')) {
                $this->error('令牌已过期，请重新提交。');
            }
        }
        $this->auth = Auth::getInstance();

        // 获取当前控制器
        $controller = $this->request->controller();
        // 获取当前方法名
        $action = $this->request->action();
        // 判断 当前方法是否在排除列表中
        if (in_array($action, $this->checkLoginExclude)) {
            // 如果存在，则无需执行后续代码
            return;
        }

        // 判断用户是否已登录
        if (!$this->auth->isLogin()) {
            // 若没有登录，提示用户登录并跳转到登录页面
            $this->error('您还没有登录。', 'Index/login');
        }
        
        // 验证用户是否有权访问
        if (!$this->auth->checkAuth($controller, $action)) {
            $this->error('您没有权限访问。');
        }
        // 将当前登录用户的id 和 用户名 传递给模板
        $loginUser = $this->auth->getLoginUser();
        $this->assign('layout_login_user', ['id' => $loginUser['id'], 'username' => $loginUser['username']]);

        // 判断是否为 Ajax 请求 如果不是, 则返回完整页面 | 如果是, 则返回内容区域
        if (!$this->request->isAjax()) {
            // 启用模板布局
            $this->view->engine->layout('common/layout');
            // 将获取到的菜单数据放入模板中
            $this->assign('layout_menu', $this->auth->menu($controller));
            // 将令牌传递给页面
            $this->assign('layout_token', $this->getToken());
        }
    }

    /**
     * 获取令牌
     */
    public function getToken()
    {
        $token = Session::get('X-CSRF-TOKEN');
        if (!$token) {
            $token = md5(uniqid(microtime(), true));
            Session::set('X-CSRF-TOKEN', $token);
        }
        return $token;
    }
}
