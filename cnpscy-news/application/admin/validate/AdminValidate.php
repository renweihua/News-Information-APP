<?php

namespace app\admin\validate;

use think\Validate;

class AdminValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'admin_id' => 'require',
        'admin_name' => 'require|max:50|unique:admins',//|token:__hash__
        'admin_email' => 'require|max:50|email',
        'is_check' => 'number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'admin_id.require' => '管理员Id为必填项！',
        'admin_name.require' => '管理员名称为必填项！',
        'admin_email.require' => '管理员邮箱为必填项！',
        'admin_email.email' => '管理员邮箱格式非法！',
        'is_check.number' => '管理员账户状态为必选项！',
    ];

    protected $scene = [
        'add' => ['admin_name', 'admin_email', 'is_check'],
        'edit' => ['admin_id', 'admin_name', 'admin_email', 'is_check'],
    ];
}
