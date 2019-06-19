<?php

namespace app\admin\validate;

use think\Validate;

class BankValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'bank_id' => 'require',
        'bank_name' => 'require|max:50|unique:banks',//|token:__hash__
        'bank_img' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'bank_id.require' => '银行Id为必填项！',
        'bank_name.require' => '银行名称为必填项！',
        'bank_img.require' => '银行图标为必填项！',
    ];

    protected $scene = [
        'add' => ['bank_name', 'bank_img'],
        'edit' => ['bank_id', 'bank_name', 'bank_img'],
    ];
}
