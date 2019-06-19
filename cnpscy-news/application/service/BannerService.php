<?php

namespace app\service;

use app\common\model\Banners;

class BannerService extends CommonService
{
    public static $where = [
        'is_check' => 1,
        'is_delete' => 0,
    ];

    public static function getWebList($params = [])
    {
        $data_list = Banners::where([
            'is_public' => 1,
        ])->where($params['where'] ?? [])
            ->where(self::$where)
            ->with(['articleInfo'])
            ->hidden(['is_delete', 'is_check', 'update_time'])
            ->order([
                'set_top' => 'desc',
                'is_recommend' => 'desc',
                'create_time' => 'desc'
            ])
            ->paginate(10)
            ->toArray();
        if (!empty($data_list['data'])) {
            foreach ($data_list['data'] as &$value) {
                $value['create_date'] = date('Y-m-d', $value['create_time']);
                $value['create_date_his'] = date('Y-m-d H:i', $value['create_time']);
            }
        }
        return $data_list;
    }

    public static function getWebDetail($article_id = 0)
    {
        $data = Articles::where([
            'is_check' => 1,
        ])->where(self::$where)->with(['articleInfo'])->find($article_id);
        if (empty($data)) return [];
        $data = $data->toArray();
        $data['create_date'] = date('Y-m-d H:i:s', $data['create_time']);
        return $data;
    }

    /**
     * 文章浏览次数+1
     * @param int $article_id
     */
    public static function setArticleRead(int $article_id = 0)
    {
        Articles::where(['article_id' => $article_id])->where(self::$where)->setInc('read_num');
    }

    /**
     * 会员发布文章
     * @param array $params
     * @return \think\response\Json
     */
    public static function create(array $params = [])
    {
        $rule = [
            'user_id' => 'require',
            // 'is_public' => [
            //     'require',
            //     'number',
            // ],
        ];
        $msg = [
            'user_id.require' => '请先登录',
            // 'is_public.require' => '文章公开度为必选项',
            // 'is_public.number' => '文章公开度标识非法',
        ];
        $Validate = new Validate($rule, $msg);
        $result = $Validate->check($params);
        if (!$result) return self::apiWebReturn(['msg' => $Validate->getError()]);

        if (Articles::create([
            'user_id' => intval($params['user_id'] ?? 0),
            'article_title' => cutStr($params['article_content'] ?? "", 0, 20),
            'article_cover' => trim($params['article_cover'] ?? ""),
            'is_public' => intval($params['is_public'] ?? 1),
            'article_type' => intval($params['article_type'] ?? 0),
        ])) return self::apiWebReturn(['status' => 1, 'msg' => '动态发布成功']);
        else return self::apiWebReturn(['msg' => '动态发布失败']);
    }

    /**
     * 文章更新
     * @param array $params
     * @return \think\response\Json
     */
    public static function update(array $params = [])
    {
        $rule = [
            'user_id' => 'require',
            'article_id' => 'require',
            'article_title' => [
                'require',
                function ($value) use ($params) {
                    if (Articles::where('article_title', $value)->where('article_id', '!=', $params['article_id'])->find()) return true;
                    else return '该文章标题已存在！';
                },
            ],
            'is_public' => [
                'require',
                'number',
            ],
        ];
        $msg = [
            'user_id.require' => '请先登录',
            'article_id.require' => '文章标识为必填项',
            'article_title.require' => '文章标题为必填项',
            'is_public.require' => '文章公开度为必选项',
            'is_public.number' => '文章公开度标识非法',
        ];
        $Validate = new Validate($rule, $msg);
        $result = $Validate->check($params);
        if (!$result) return self::apiWebReturn(['msg' => $Validate->getError()]);
        $article_id = intval($params['article_id'] ?? 0);
        $user_id = intval($params['user_id'] ?? 0);
        $where = [
            'article_id' => $article_id,
            'user_id' => $user_id,
        ];
        if (empty($article = Articles::where($where)->where(self::$where)->lock(true)->find())) return self::apiWebReturn(['msg' => '文章不存在']);

        if (Articles::where('article_id', $article['article_id'])->where(self::$where)->update([
            'article_title' => trim($params['article_title'] ?? ""),
            'article_cover' => trim($params['article_cover'] ?? ""),
            'article_keywords' => trim($params['article_keywords'] ?? ""),
            'article_description' => trim($params['article_description'] ?? ""),
            'is_public' => intval($params['is_public'] ?? 1),
        ])) return self::apiWebReturn(['status' => 1, 'msg' => '文章更新成功']);
        else return self::apiWebReturn(['msg' => '文章更新失败']);
    }

    public static function delete(array $params = [])
    {
        $rule = [
            'user_id' => 'require',
            'article_id' => 'require',
        ];
        $msg = [
            'user_id.require' => '请先登录',
            'article_id.require' => '文章标识为必填项',
        ];
        $Validate = new Validate($rule, $msg);
        $result = $Validate->check($params);
        if (!$result) return self::apiWebReturn(['msg' => $Validate->getError()]);
        $article_id = intval($params['article_id'] ?? 0);
        $user_id = intval($params['user_id'] ?? 0);
        $where = [
            'article_id' => $article_id,
            'user_id' => $user_id,
        ];
        if (empty($article = Articles::where($where)->where(self::$where)->lock(true)->find())) return self::apiWebReturn(['msg' => '文章不存在']);

        if (Articles::where('article_id', $article['article_id'])->where(self::$where)->update(['is_delete' => 1])) return self::apiWebReturn(['status' => 1, 'msg' => '文章删除成功']);
        else return self::apiWebReturn(['msg' => '文章删除失败']);
    }
}