<?php

namespace app\service;

use app\common\event\admin\AdminLoginEvent;
use app\common\model\Rabc\Admins;
use think\Validate;

Class AdminService extends CommonService
{
    /**
     * [login 后台管理员登录]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public static function login($params = [])
    {
        $admin = Admins::where('admin_name', $params['admin_name'] ?? '')->lock(true)->find();
        $rule = [
            'admin_name' => [
                'require',
                function ($value) use ($admin) {
                    if (empty($admin)) return '账户不存在!';
                    if (intval($admin->is_check) == 0) return '您的账户已被禁用';
                    else return true;
                }
            ],
            'password' => [
                'require',
                'length:6,25',
                function ($value) use ($admin) {
                    if (hash_verify($value, $admin->password)) return true;
                    else return '账户与密码不匹配';
                }
            ],
        ];
        $Validate = new Validate($rule, [
            'user_name.require' => '账户为必填项',
            'password.require' => '密码为必填项',
            'password.length' => '密码长度应在6~25位之间',
        ]);
        $result = $Validate->check($params);
        if (!$result) return self::apiAdminReturn(['msg' => $Validate->getError()]);

        //管理员信息
        session(cnpscy_config('admin_info_session_unique'), $admin);

        //管理员登录事件处理
        AdminLoginEvent::init($params, $admin);
        
        return self::apiAdminReturn(['msg' => '登录成功', 'status' => 1]);
    }
}