<?php

namespace app\admin\validate;

use think\Validate;

class ExpressValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'express_id' => 'require',
        'express_name' => 'require|max:50|unique:express',//|token:__hash__
        'express_code' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'express_id.require' => '快递Id为必填项！',
        'express_name.require' => '快递名称为必填项！',
        'express_code.require' => '快递标识为必填项！',
    ];

    protected $scene = [
        'add' => ['express_name', 'express_code'],
        'edit' => ['express_id', 'express_name', 'express_code'],
    ];
}
