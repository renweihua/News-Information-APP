<?php

namespace app\admin\controller;

class Apiweblogs extends Common
{
    public function initialize()
    {
        $this->model = model('./Log/ApiWebLogs');
        parent::initialize();
    }
}