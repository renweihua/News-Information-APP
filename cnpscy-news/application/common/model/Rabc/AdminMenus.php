<?php

namespace app\common\model\Rabc;

use app\common\model\Common;

class AdminMenus extends Common
{
    public $pk = 'menu_id';

    protected $autoWriteTimestamp = true;// 开启自动写入时间戳

    public static function getAdminmenusList($where = []): array
    {
        return \list_to_tree((array)self::where($where)
            ->order('menu_sort', 'ASC')->select()->toArray());
    }
}
