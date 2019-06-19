<?php

namespace app\common\model\Article;

use app\common\model\Common;

class Articles extends Common
{
    public $pk = 'article_id';
    public $is_delete = 0;
    protected $autoWriteTimestamp = true;// 开启自动写入时间戳
    public $detailWithModel = ['articleInfo'];//详情关联模型

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['article_title|article_keywords', 'like', $params['search'] . '%'];

        if (isset($params['set_top']) && intval($params['set_top'] ?? -1) != -1) $params['where'][] = ['set_top', '=', intval($params['set_top'] ?? -1)];

        if (isset($params['is_recommend']) && intval($params['is_recommend'] ?? -1) != -1) $params['where'][] = ['is_recommend', '=', intval($params['is_recommend'] ?? -1)];

        if (isset($params['is_public']) && intval($params['is_public'] ?? -1) != -1) $params['where'][] = ['is_public', '=', intval($params['is_public'] ?? -1)];

        if (isset($params['is_check']) && intval($params['is_check'] ?? -1) != -1) $params['where'][] = ['is_check', '=', intval($params['is_check'] ?? -1)];
        return $params;
    }

    public static function init()
    {
        $params = input();
        $client_info = get_client_info();
        self::afterInsert(function ($model) use ($params, $client_info) {
            if (!$model->articleInfo()->save([
                $model->pk => $model->{$model->pk},
                'article_content' => $params['article_content'] ?? "",
                'create_ip' => $client_info['ip'],
                'browser_type' => $client_info['agent'],
            ])) $model->delete();
        }, false);

        self::afterUpdate(function ($model) use ($params, $client_info) {
            if (ArticleDatas::find($model->{$model->pk})) ArticleDatas::where([$model->pk => $model->{$model->pk}])->update(['article_content' => $params['article_content']]);
            else ArticleDatas::create([
                $model->pk => $model->{$model->pk},
                'article_content' => $params['article_content'] ?? "",
                'create_ip' => $client_info['ip'],
                'browser_type' => $client_info['agent'],
            ]);
        });
    }

    public function articleInfo()
    {
        return $this->hasOne(ArticleDatas::class, 'article_id', 'article_id');
    }
}
