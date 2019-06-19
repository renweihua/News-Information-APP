<?php

namespace app\common\model\Article;

use app\common\model\Common;

class ArticleCategorys extends Common
{
    public $pk = 'category_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;// 开启自动写入时间戳

    public static function getCategorySelectList($where = []): array
    {
        return \list_to_tree((array)self::where($where)
            ->order('category_rank', 'ASC')
            ->select()
            ->toArray(),
            'category_id');
    }
}
