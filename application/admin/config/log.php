<?php

/**
 * 配置SocketLog服务器连接信息
 * @author: Kuiperbelt
 * @email: kuiperabxy@outlook.com
 * @date: 2021/05/02 01:33
 */
return [
    'type'              => 'socket',
    'host'              => '127.0.0.1',
    // 只允许指定的客户端id读取日志
    'allow_client_ids'  => ['thinkphp_Kuiperbelt', 'thinkphp_Kuiperabxy']
];
