<?php

namespace app\common\model\Log;

use app\common\model\Common;
use app\common\model\User\Users;

class ApiWebLogs extends Common
{
    public $pk = 'log_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['log_action|log_method|create_ip', 'like', $params['search'] . '%'];

        if (isset($params['user_id']) && !empty(intval($params['user_id'] ?? 0))) $params['where'][] = ['user_id', '=', intval($params['user_id'] ?? 0)];

        if (isset($params['is_ok']) && intval($params['is_ok'] ?? -1) != -1) $params['where'][] = ['is_ok', '=', intval($params['is_ok'] ?? -1)];
        return $params;
    }
}
