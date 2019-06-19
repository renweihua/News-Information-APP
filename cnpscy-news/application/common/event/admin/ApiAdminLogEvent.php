<?php

namespace app\common\event\admin;

use app\common\interfaces\Event;
use app\common\model\Log\ApiAdminLogs;

/**
 * 后台API日志记录
 * Class AdminLoginEvent
 * @package app\common\event
 */
class ApiAdminLogEvent implements Event
{
    public static function init(array $params = [], $admin_id = 0)
    {
        $client_info = get_client_info();

        //登录日志记录
        ApiAdminLogs::create([
            'admin_id' => intval($admin_id ?? 0),
            'create_ip' => $client_info['ip'],
            'browser_type' => $client_info['agent'],
            'log_description' => $params['msg'] ?? '',
            'is_ok' => intval($params['status'] ?? 0),
            'log_action' => request()->module() . '/' . request()->controller() . '/' . request()->action(),
            'log_method' => request()->method(),
            'request_data' => json_encode($params),
        ]);
    }
}