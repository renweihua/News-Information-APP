<?php

namespace app\admin\controller;

class Indexs extends Common
{
    public $layout = '';

    public function initialize()
    {
        parent::initialize();

        //菜单栏目
        $admin_menus = [];
        if (intval(session(cnpscy_config('admin_info_session_unique') . '.admin_id')) == 1)
            $admin_menus = \app\common\model\Rabc\AdminMenus::getAdminmenusList();
        else $admin_menus = session(cnpscy_config('admin_menu_session_unique'));
        
        $this->assign('admin_menus', $admin_menus);

        $admin_info = session(cnpscy_config('admin_info_session_unique'));
        $admin_info['roles'] = empty($admin_info['roles']) ? [] : $admin_info['roles'];
        $admin_info['roles'][] = empty($admin_info['roles']) ? [] : $admin_info['roles'][0];
        $admin_info['roles'][0]['role_name'] = empty($admin_info['roles'][0]['role_name']) ? '管理员' : $admin_info['roles'][0]['role_name'];
        $this->assign('admin_info', $admin_info);
    }

    public function main()
    {
        return $this->fetch();
    }
}