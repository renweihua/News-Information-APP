<?php

namespace app\api\controller;

use app\common\traits\CommonController;
use think\App;
use think\Controller;
use think\Request;

class Common extends Controller
{
    use CommonController;

    public static $user_id;
    public static $params;

    public function initialize()
    {
        date_default_timezone_set(cnpscy_config('system_timezone'));//设置时区

        self::$user_id = 1;
        self::setLoginUserId(new Request());
        self::$params = input();
        self::$params['user_id'] = self::$user_id;
    }

    /**
     * 设置登录会员Id
     */
    public static function setLoginUserId($request)
    {
        self::$user_id = intval($request->{cnpscy_config('server_user_unqiue')} ?? 0);
    }

    public static function checkUserLogin()
    {
        self::setLoginUserId(new Request());
        if (empty(self::$user_id)) return self::apiWebReturn([
            'msg' => '请先登录',
            'status' => lang('_UN_LOGIN_STAUT_')
        ]);
    }

    public static function webFormatReturn(array $data_list = [])
    {
        return self::apiWebReturn([
            'data' => [
                'cur_page' => intval($data_list['current_page'] ?? 1),
                'page_size' => intval($data_list['per_page'] ?? 10),
                'count_page' => intval($data_list['last_page'] ?? 0),
                'count' => intval($data_list['total'] ?? 0),
                'data' => $data_list['data'] ?? [],
            ],
            'msg' => $data_list['msg'] ?? "",
            'status' => 1
        ]);
    }
}
