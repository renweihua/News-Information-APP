<?php

namespace app\service;

use app\common\model\Configs;

class ConfigService extends CommonService
{
    /**
     * 后端分组排序列表
     * @return array
     */
    public static function getAdminGroupList()
    {
        $configs = Configs::where([
            'is_check' => 1,
            'is_delete' => 0,
        ])->all()->toArray();
        if (!empty($configs)) {
            foreach ($configs as &$v) {
                if (in_array($v['config_type'], [4])){
                    if (!empty($v['config_extra'])) $v['config_extra'] = config_array_analysis($v['config_extra']);
                }
            }
        }
        $configs = array_field_group($configs, 'config_group');//按照配置进行分组
        if (empty($configs[0])) $configs[0] = [];
        return $configs;
    }
}