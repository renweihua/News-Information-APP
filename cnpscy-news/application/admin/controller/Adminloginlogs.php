<?php

namespace app\admin\controller;

class Adminloginlogs extends Common
{
    public function initialize()
    {
        $this->model = model('./Rabc/AdminLoginLogs');
        parent::initialize();
    }
}