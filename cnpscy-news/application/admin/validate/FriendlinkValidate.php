<?php

namespace app\admin\validate;

use think\Validate;

class FriendlinkValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'link_id' => 'require',
        'link_name' => 'require|max:50|unique:friendlinks',//|token:__hash__
        'link_img' => 'require',
        'link_url' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'link_id.require' => '文章Id为必填项！',
        'link_name.require' => '友情链接站点名称为必填项！',
        'link_img.require' => '站点图标为必传项！',
        'link_url.require' => '站点网址为必填项！',
    ];

    protected $scene = [
        'add' => ['link_name', 'link_img', 'link_url'],
        'edit' => ['link_id', 'link_name', 'link_img', 'link_url'],
    ];
}
