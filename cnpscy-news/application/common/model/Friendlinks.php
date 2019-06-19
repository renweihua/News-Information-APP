<?php

namespace app\common\model;

class Friendlinks extends Common
{
    public $pk = 'link_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['link_name', 'like', $params['search'] . '%'];
        return $params;
    }
}
