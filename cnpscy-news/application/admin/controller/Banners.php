<?php

namespace app\admin\controller;

class Banners extends Common
{
    public function initialize()
    {
        $this->model = model('Banners');
        $this->requestValidator = 'BannerValidate';
        parent::initialize();
    }
}
