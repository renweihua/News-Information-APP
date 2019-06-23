<?php

namespace app\common\event\user;

use app\common\interfaces\Event;
use app\common\model\User\UserLoginLogs;

/**
 * 登录
 * Class AdminLoginEvent
 * @package app\common\event
 */
class UserLoginEvent implements Event
{
    public static function init(array $params = [], $user)
    {
        $client_info = get_client_info();
        $user_id = intval($user['id'] ?? 0);
        // 登录日志
        UserLoginLogs::create([
            'user_id' => $user_id,
            'create_ip' => $client_info['ip'],
            'browser_type' => $client_info['agent'],
        ]);
    }
}