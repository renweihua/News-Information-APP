<?php

namespace app\admin\controller;

class Adminmenus extends Common
{
    public function initialize()
    {
        $this->model = model('./Rabc/AdminMenus');
        parent::initialize();
    }

    public function getAdminmenusList()
    {
        return json([
            'data' => (array)$this->model->getAdminmenusList([
                'is_left' => 1,
                'is_check' => 1,
                'is_delete' => 0,
            ]),
            'status' => 1
        ]);
    }

    public function getselectlists()
    {
        return json([
            'data' => (array)$this->model->getAdminmenusList([
                'is_delete' => 0,
            ]),
            'status' => 1,
        ]);
    }
}