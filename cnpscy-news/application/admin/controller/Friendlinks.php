<?php

namespace app\admin\controller;

class Friendlinks extends Common
{
    public function initialize()
    {
        $this->model = model('Friendlinks');
        $this->requestValidator = 'FriendlinkValidate';
        parent::initialize();
    }
}