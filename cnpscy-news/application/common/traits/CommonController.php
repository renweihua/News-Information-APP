<?php

namespace app\common\traits;

use app\common\event\admin\ApiAdminLogEvent;
use app\common\event\user\ApiUserLogEvent;
use app\service\LoginService;

trait CommonController
{
    /**
     * [myAjaxReturn]
     * @author:cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:API接口返回格式统一
     * @englishAnnotation:
     * @version:1.0
     * @param              [type] $data [description]
     */
    public static function myAjaxReturn($data)
    {
        return json($data, 200);
        return json(self::checkAjaxReturn($data), 200);
        exit;
    }

    public static function myJsonReturn($data = [])
    {
        return json($data, 200);
    }

    /**
     * [check_error]
     * @author:cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:
     * @englishAnnotation:
     * @version:1.0
     * @param              Request $request [description]
     * @return             [type]           [description]
     */
    public static function check_error(Request $request)
    {
        return $request->only(['status', 'data', 'msg', 'code']);
    }

    /**
     * [checkAjaxReturn]
     * @author:cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:检测返回的数组，参数是否匹配，不匹配主动生成空
     * @englishAnnotation:
     * @version:1.0
     * @param              [type] $data [description]
     */
    public static function checkAjaxReturn(array $data = [])
    {
        $data['data'] = $data['data'] ?? [];
        $data['status'] = intval($data['status'] ?? (empty($data['data']) ? 0 : 1));
        $data['msg'] = $data['msg'] ?? (empty($data['status']) ? '数据不存在！' : '');

        if (!empty($user_id = \request()->{cnpscy_config('server_user_unqiue')})) {
            $token = [
                "user_id" => $user_id,
                'life_time' => cnpscy_config('jwt_leeway') + time(),
            ];
            $data[cnpscy_config('return_jwt_token_name')] = LoginService::jwt_encode($token);
        }
        if ($data['msg'] == '请先登录') $data['status'] = lang('_UN_LOGIN_STAUT_');
        $data['config'] = cnpscy_config('');
        return $data;
    }

    /**
     * 后端的接口过滤
     */
    public static function apiAdminReturn(array $return = [])
    {
        $data = self::checkAjaxReturn($return);

        ApiAdminLogEvent::init($data, session(cnpscy_config('admin_info_session_unique') . '.admin_id'));

        return self::myAjaxReturn($data);
    }

    /**
     * 前端的接口过滤
     */
    public static function apiWebReturn(array $return = [])
    {
        $data = self::checkAjaxReturn($return);

        ApiUserLogEvent::init($data, intval($request->{cnpscy_config('server_user_unqiue')} ?? 0));

        return self::myAjaxReturn($data);
    }
}