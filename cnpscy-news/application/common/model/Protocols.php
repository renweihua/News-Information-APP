<?php

namespace app\common\model;

class Protocols extends Common
{
    public $pk = 'protocol_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;

    public function setSearchWhereFilter(array $params = [])
    {
        $protocol_type = ($params['protocol_type'] ?? -1);
        if (isset($params['protocol_type']) && $protocol_type != -1) $params['where'][] = ['protocol_type', '=',$protocol_type ];
        return $params;
    }
}
