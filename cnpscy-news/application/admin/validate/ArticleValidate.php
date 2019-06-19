<?php

namespace app\admin\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'article_id' => 'require',
        'article_title' => 'require|max:50|unique:articles',//|token:__hash__
        'article_cover' => 'require',
        'is_check' => 'require',
        'is_public' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'article_id.require' => '文章Id为必填项！',
        'article_title.require' => '文章标题为必填项！',
        'article_cover.require' => '文章封面为必传项！',
        'is_check.number' => '文章审核状态为必选项！',
        'is_public.number' => '文章公开状态为必选项！',
    ];

    protected $scene = [
        'add' => ['article_title', 'article_cover', 'is_public', 'is_check'],
        'edit' => ['article_id', 'article_title', 'article_cover', 'is_public', 'is_check'],
    ];
}
