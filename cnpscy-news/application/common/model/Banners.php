<?php

namespace app\common\model;

class Banners extends Common
{
    public $pk = 'banner_id';
    public $is_delete = 0;//是否删除
    protected $autoWriteTimestamp = true;// 开启自动写入时间戳

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['banner_title|banner_words', 'like', $params['search'] . '%'];
        return $params;
    }
}
