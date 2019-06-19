<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php'],

    //公共模块
    'common' => [
        '__file__' => ['common.php'],
        '__dir__' => ['behavior', 'controller', 'model', 'events', 'listeners', 'interfaces', 'traits', 'exception'],
        'controller' => ['Common'],
        'model' => ['Common'],
        'interfaces' => ['AstrictApi'],
        'traits' => ['CommonController'],
    ],

    //后台模块
    'admin' => [
        '__file__' => ['common.php'],
        '__dir__' => ['behavior', 'controller'],
        'controller' => ['Common', 'Admins', 'Roles', 'Adminmenus', 'Configs', 'Indexs', 'Logins', 'Menus', 'Friendlinks'],
        'view' => [
            'default/logins/index',

            'default/indexs/index',

            'default/public/success',
            'default/public/error',
            'default/public/header',
            'default/public/footer',
            'default/public/sidebar',
            'default/public/main',

            'default/admins/index',
            'default/admins/detail',

            'default/roles/index',
            'default/roles/detail',

            'default/adminmenus/index',
            'default/adminmenus/detail',

            'default/configs/index',
            'default/configs/detail',

            'default/menus/index',
            'default/menus/detail',

            'default/friendlinks/index',
            'default/friendlinks/detail',
        ],
    ],
    // 其他更多的模块定义
];
