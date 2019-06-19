<?php

namespace app\admin\validate;

use think\Validate;

class RoleValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'role_id' => 'require',
        'role_name' => 'require|max:50|unique:roles',//|token:__hash__
        'is_check' => 'number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'role_id.require' => '角色Id为必填项！',
        'role_name.require' => '角色名称为必填项！',
        'is_check.number' => '角色账户状态为必选项！',
    ];

    protected $scene = [
        'add' => ['role_name', 'is_check'],
        'edit' => ['role_id', 'role_name', 'is_check'],
    ];
}
