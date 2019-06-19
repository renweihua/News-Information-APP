<?php

namespace app\admin\controller;

class Apiadminlogs extends Common
{
    public function initialize()
    {
        $this->model = model('./Log/ApiAdminLogs');
        parent::initialize();
    }
}