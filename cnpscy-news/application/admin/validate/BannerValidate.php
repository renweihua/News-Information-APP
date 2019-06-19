<?php

namespace app\admin\validate;

use think\Validate;

class BannerValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'banner_id' => 'require',
        'banner_title' => 'require|max:50',//|token:__hash__
        'banner_cover' => 'require',
        'is_check' => 'number',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'banner_id.require' => 'BannerId为必填项！',
        'banner_title.require' => 'Banner标题为必填项！',
        'banner_title.max' => 'Banner标题最大长度为50字符！',
        'banner_cover.require' => 'Banner封面为必填项！',
        'is_check.number' => 'Banner状态为必选项！',
    ];

    protected $scene = [
        'add' => ['banner_title', 'banner_cover', 'is_check'],
        'edit' => ['banner_id', 'banner_title', 'banner_cover', 'is_check'],
    ];
}
