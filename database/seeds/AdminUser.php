<?php

use think\migration\Seeder;

class AdminUser extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        /**
         * @param   salt        自动生成密钥 
         * @method  microtime   返回当前UNIX时间戳和微秒数
         * @method  uniqid      生成一个唯一ID
         * @method  md5         计算字符串的MD5散列值
         */
        $salt = md5(uniqid(microtime(), true));

        /**
         * @param   password    对密码进行加密,密码原文为 123456
         */
        $password = md5(md5('123456') . $salt);

        /**
         * 添加数据
         * @method  table   绑定表名
         * @method  insert  插入数据
         * @method  save    保存数据
         */
        $this->table('admin_user')->insert([
            ['id' => 1, 'admin_role_id' => 1, 'username' => 'admin', 'password' => $password, 'salt' => $salt],
            ['id' => 2, 'admin_role_id' => 2, 'username' => 'test', 'password' => $password, 'salt' => $salt]
        ])->save();
    }
}
