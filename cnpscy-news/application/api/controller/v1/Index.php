<?php

namespace app\api\controller\v1;

use app\api\controller\Common;

class Index extends Common
{
    public function index()
    {
    }

    public function config()
    {
        if (request()->isPost()){
            $config = cnpscy_config('');
            $config['site_web_logo'] = web_url() . $config['site_web_logo'];
            return self::apiWebReturn([
                'data' => $config,
            ]);
        } else return $this->apiWebReturn(['msg' => '非法请求']);
    }
}
