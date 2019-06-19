<?php

namespace app\admin\controller;

use app\service\ConfigService;
use think\Request;

class Websites extends Common
{
    public function initialize()
    {
        $this->model = model('Configs');
        parent::initialize();
    }

    public function index($extends_info = [])
    {
        $this->assign('configs_list', ConfigService::getAdminGroupList());
        $this->assign('config_group_list', cnpscy_config('config_group_list'));
        return parent::index();
    }

    public function update(Request $request)
    {
        if (request()->isPost())
        {
            $request_data = $request->param();
            $model = $this->model;
            foreach ($request_data as $key => $value) {
                if ($config = $model->where('config_name', $key)->lock(true)->find()){
                    if ($value != $config['config_value']) $model->where('config_name', $key)->update([ 'config_value' => trim($value) ]);
                }else $model->insert([
                    'config_name' => trim($key),
                    'config_value' => trim($value),
                ]);
            }
            $model::pushRefreshConfig();
            return self::apiAdminReturn(['msg' => '批量更新成功', 'status' => 1]);
        }
    }
}