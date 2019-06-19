<?php

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\service\ArticleService;

class Articles extends Common
{
    public function index()
    {
        if (request()->isPost()){
            $params = input();
            return $this->webFormatReturn(ArticleService::getWebList($params));
        } else return $this->apiWebReturn(['msg' => '非法请求']);
    }

    /**
     * 文章详情
     */
    public function detail(int $article_id = 0)
    {
        if (request()->isPost()){
            return self::apiWebReturn([
                    'data' => ArticleService::getWebDetail($article_id),
                ]);
        } else return $this->apiWebReturn(['msg' => '非法请求']);
    }
}
