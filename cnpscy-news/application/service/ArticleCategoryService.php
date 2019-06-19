<?php

namespace app\service;

use app\common\model\Article\ArticleCategorys;

class ArticleCategoryService extends CommonService
{
    public static $where = [
        'is_check' => 1,
        'is_delete' => 0,
        'parent_id' => 0,
    ];

    public static function getWebList($params = [])
    {
        $data_list = ArticleCategorys::where($params['where'] ?? [])
            ->where(self::$where)
            ->hidden(['parent_id', 'is_check', 'is_delete', 'update_time'])
            ->order([
                'category_sort' => 'ASC',
                'create_time' => 'ASC'
            ])
            ->limit(10)
            ->select();
        return $data_list;
    }
}