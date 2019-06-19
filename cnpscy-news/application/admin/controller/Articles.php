<?php

namespace app\admin\controller;

class Articles extends Common
{
    public function initialize()
    {
        $this->model = model('./Article/Articles');
        $this->requestValidator = 'ArticleValidate';
        parent::initialize();
    }
}