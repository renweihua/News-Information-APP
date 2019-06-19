<?php

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\service\ArticleCategoryService;

class ArticleCategorys extends Common
{
    public function index()
    {
        $params = input();
        if (request()->isPost()){
            return $this->apiWebReturn([
                    'data' => ArticleCategoryService::getWebList($params),
                    'status' => 1,
                    'msg' => '文章分类获取成功',
                ]);
        } else return $this->apiWebReturn(['msg' => '非法请求']);
    }
}
