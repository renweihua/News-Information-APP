<?php

namespace app\admin\validate;

use think\Validate;

class LoginValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'admin_name' => 'require',
        'password' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'admin_name.require' => '管理员账户为必填项！',
        'password.require' => '管理员密码为必填项！',
    ];
}
