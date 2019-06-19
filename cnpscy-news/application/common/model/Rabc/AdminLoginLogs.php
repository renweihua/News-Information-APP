<?php

namespace app\common\model\Rabc;

use app\common\model\Common;

class AdminLoginLogs extends Common
{
    public $pk = 'log_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;
    public $withModel = ['admin'];//关联模型

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['log_action|log_method|created_ip', 'like', $params['search'] . '%'];

        if (isset($params['admin_id']) && !empty(intval($params['admin_id'] ?? 0))) $params['where'][] = ['admin_id', '=', intval($params['admin_id'] ?? 0)];

        if (isset($params['is_ok']) && intval($params['is_ok'] ?? -1) != -1) $params['where'][] = ['is_ok', '=', intval($params['is_ok'] ?? -1)];
        return $params;
    }

    public function admin()
    {
        return $this->hasOne(Admins::class, 'admin_id', 'admin_id');
    }
}
