<?php

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\model\Banners as Banner;

class Banners extends Common
{
    /**
     * 首页Banner
     */
    public function index()
    {
        if (request()->isPost()){
            return self::apiWebReturn([
                'data' => Banner::where('is_check', 1)
                    ->hidden(['is_delete', 'is_check', 'create_time', 'update_time'])
                    ->limit(10)
                    ->select()
            ]);
        } else return $this->apiWebReturn(['msg' => '非法请求']);
    }
}
