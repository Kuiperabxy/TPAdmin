<?php

/**
 * Admin 后台登录入口控制器
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/04/29 22:36
 */

namespace app\admin\controller;

use app\admin\validate\AdminUser as UserValidate;
use think\App;
use think\Db;

/**
 * 后台首页 控制器
 */
class Index extends Common
{
    /**
     * @param   checkLoginExclude    排除列表, 表示定义在该数组中的方法名将不需要检测用户是否登录
     */
    protected $checkLoginExclude = ['login'];

    /**
     * 用户登录
     */
    public function login()
    {
        // 获取数据,验证表单
        if ($this->request->isPost()) {
            $data = [
                'username'  => $this->request->post('username/s', '', 'trim'),
                'password'  => $this->request->post('password/s', ''),
                'captcha'   => $this->request->post('captcha/s', '', 'trim')
            ];
            // 验证数据 格式
            $validate = new UserValidate;

            if (!$validate->scene('login')->check($data)) {
                $this->error('登录失败：' . $validate->getError() . '。');
            }
            // 执行到此处说明验证器验证成功

            // 验证数据 准确性
            if (!$this->auth->login($data['username'], $data['password'])) {
                $this->error('登录失败：' . $this->auth->getError() . '。');
            }

            $this->success('登录成功。');
        }
        // 为页面生成令牌
        $this->assign('token', $this->getToken());

        return $this->fetch();
    }
    /**
     * 用户退出
     */
    public function logout()
    {
        $this->auth->logout();
        // 重定向
        $this->redirect('Index/login');
    }
    /**
     * 页面主函数
     */
    public function index(App $app)
    {
        // 获取服务器信息
        $this->assign('server_info', [
            'server_version'        => $this->request->server('SERVER_SOFTWARE'),
            'thinkphp_version'      => $app->version(),
            'mysql_version'         => $this->getMySQLVer(),
            'server_time'           => date('Y-m-d H:i:s', time()),
            // 文件上传限制
            'upload_max_filesize'   => ini_get('file_uploads') ? ini_get('upload_max_filesize') : '已禁用',
            // 脚本执行时限
            'max_execution_time'    => ini_get('max_execution_time') . '秒'
        ]);
        return $this->fetch();
    }
    /**
     * 获取MySQL版本
     */
    private function getMySQLVer()
    {
        // 通过查询 获取当前MySQL版本
        $sql = 'SELECT VERSION()';
        $res = Db::query($sql);
        return isset($res[0]) ? $res[0]['VERSION()'] : '未知版本';
    }
    /**
     * 修改密码
     */
    public function password()
    {
        if ($this->request->isPost()) {
            $password = $this->request->post('password/s', '');
            $this->auth->changePassword($password);
            $this->success('密码修改成功。');
        }
        return $this->fetch();
    }
}
