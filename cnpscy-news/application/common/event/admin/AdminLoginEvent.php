<?php

namespace app\common\event\admin;

use app\common\interfaces\Event;
use app\common\model\Rabc\AdminInfos;
use app\common\model\Rabc\AdminLoginLogs;
use app\common\traits\CommonController;

/**
 * 管理员登录
 * Class AdminLoginEvent
 * @package app\common\event
 */
class AdminLoginEvent implements Event
{
    public static function init(array $params = [], $admin)
    {
        $client_info = get_client_info();
        $admin_id = $admin->admin_id ?? 0;

        //登录日志记录
        AdminLoginLogs::create([
            'admin_id' => $admin_id,
            'create_ip' => $client_info['ip'],
            'browser_type' => $client_info['agent'],
            'description' => '登录成功',
            'log_action' => request()->module() . '/' . request()->controller() . '/' . request()->action(),
            'log_method' => request()->method(),
            // 'request_data' => json_encode($params),
        ]);

        //登录次数累加
        AdminInfos::where('admin_id', $admin_id)->setInc('login_num');

        //管理员的权限获取，并且存储
        $roles = $admin->roles;
        $rabc = $menu_lists = [];
        foreach ($roles as $key => $role)
        {
            if ($key == 0) session(cnpscy_config('admin_info_session_unique') . '.use_role', $role->role_id);
            $menus = $role->menus->toArray();
            $rabc[$role->role_id] = array_flip(array_unique(array_column($menus, 'menu_url')));

            $menu_lists = array_merge($menu_lists, $menus);
        }
        session(cnpscy_config('admin_rabc_session_unique'), $rabc);//保存角色

        session(cnpscy_config('admin_menu_session_unique'), list_to_tree((array)$menu_lists));//用于展示左侧栏目
    }
}