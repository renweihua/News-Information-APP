<?php

namespace app\admin\controller;

use app\service\AdminService;
use think\Controller;

class Logins extends Controller
{
    public function index()
    {
        //if (session(cnpscy_config('admin_info_session_unique'))) $this->redirect(url('/admin/indexs'));
        if (request()->isPost()) return AdminService::login(input());
        else return $this->fetch();
    }

    public function logout()
    {
        session(cnpscy_config('admin_info_session_unique'), []);
        $this->redirect(url('index'));
    }
}