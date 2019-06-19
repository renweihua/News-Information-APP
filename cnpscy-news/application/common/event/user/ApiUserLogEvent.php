<?php

namespace app\common\event\user;

use app\common\interfaces\Event;
use app\common\model\Log\ApiWebLogs;

/**
 * 前端API日志记录
 * Class AdminLoginEvent
 * @package app\common\event
 */
class ApiUserLogEvent implements Event
{
    public static function init(array $params = [], $user_id = 0)
    {
        $client_info = get_client_info();

        //登录日志记录
        ApiWebLogs::create([
            'user_id' => $user_id,
            'create_ip' => $client_info['ip'],
            'browser_type' => $client_info['agent'],
            'log_description' => $params['msg'] ?? '',
            'is_ok' => intval($params['status'] ?? 0),
            'log_action' => request()->module() . '/' . request()->controller() . '/' . request()->action(),
            'log_method' => request()->method(),
            // 'request_data' => json_encode($params),
        ]);
    }
}