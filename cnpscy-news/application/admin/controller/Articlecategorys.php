<?php

namespace app\admin\controller;

class Articlecategorys extends Common
{
    public function initialize()
    {
        $this->model = model('./Article/ArticleCategorys');
        parent::initialize();
    }

    public function getselectlists()
    {
        return json([
            'data' => (array)$this->model->getCategorySelectList([
                'is_delete' => 0,
            ]),
            'status' => 1,
        ]);
    }
}