<?php

namespace app\common\model;

class Configs extends Common
{
    public $pk = 'config_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['config_title|config_name', 'like', $params['search'] . '%'];
        return $params;
    }

    public static function pushRefreshConfig()
    {
        $config_data = self::where([
            'is_check' => 1,
            'is_delete' => 0,
        ])->field('config_value, config_name, config_type')->all()->toArray();//字段进行过滤
        $_data = $data_list = [];
        array_walk($config_data, function ($value) use (&$data_list) {
            /**
             * 对于数组格式的处理
             *
             * in_array(strtoupper($value['config_name']), ['CONFIG_GROUP_LIST', 'CONFIG_TYPE_LIST', 'MENU_TYPE_LIST']) ||
             */
            if ($value['config_type'] == 3) {
                $value_ary = array_filter(explode('|', str_replace(array("\r", "\r\n", "\n"), '|', $value['config_value'])));
                foreach ($value_ary as $k => $v) {
                    if (empty($value['config_name'])) continue;
                    $array = explode(':', str_replace(array("'", '"', "\r", "\r\n", "\n"), '', $v));
                    $_data[$array[0]] = $array[1];
                }
                $data_list[$value['config_name']] = $_data;
            }else $data_list[$value['config_name']] = $value['config_value'];
        });
        file_put_contents(ROOT . 'config/cnpscy.php', '<?php return ' . var_export($data_list, true) . ';');
        unset($config_data, $_data, $data_list, $array, $value_ary, $data_list);
    }
}
