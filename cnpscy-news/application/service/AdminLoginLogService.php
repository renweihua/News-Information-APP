<?php

namespace app\service;


use app\common\model\Rabc\AdminLoginLogs;

Class AdminLoginLogService extends CommonService
{
    public static function getWebList(array $params = [])
    {
        $user_id = intval($params['user_id'] ?? 0);
        if (empty($user_id)) return [];

        $data_list = AdminLoginLogs::where([
            'user_id' => $user_id
        ])
            ->paginate(10)
            ->toArray();
        if (!empty($data_list['data'])) {
            foreach ($data_list['data'] as &$value) {
                $value['create_date'] = date('Y-m-d H:i', $value['create_time']);
            }
        }
        return $data_list;
    }
}