<?php

namespace app\service;

use app\common\event\admin\AdminLoginEvent;
use app\common\model\Rabc\Admins;
use think\Validate;

Class AdminService extends CommonService
{
    /**
     * [login 后台管理员登录]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    public static function login($params = [])
    {
        $admin = Admins::where('admin_name', $params['admin_name'] ?? '')->lock(true)->find();
        $rule = [
            'admin_name' => [
                'require',
                function ($value) use ($admin) {
                    if (empty($admin)) return '账户不存在!';
                    if (intval($admin->is_check) == 0) return '您的账户已被禁用';
                    else return true;
                }
            ],
            'password' => [
                'require',
                'length:6,25',
                function ($value) use ($admin) {
                    if (hash_verify($value, $admin->password)) return true;
                    else return '账户与密码不匹配';
                }
            ],
        ];
        $Validate = new Validate($rule, [
            'user_name.require' => '账户为必填项',
            'password.require' => '密码为必填项',
            'password.length' => '密码长度应在6~25位之间',
        ]);
        $result = $Validate->check($params);
        if (!$result) return self::apiAdminReturn(['msg' => $Validate->getError()]);

        //管理员信息
        session(cnpscy_config('admin_info_session_unique'), $admin);

        //管理员登录事件处理
        AdminLoginEvent::init($params, $admin);
        
        return self::apiAdminReturn(['msg' => '登录成功', 'status' => 1]);
    }

    //保存会员的基本信息
    public static function saveUserInfo($params = [])
    {
        $Validate = new Validate([
            'username' => [
                'require'
            ],
        ], [
            'username.require' => '昵称为必填项',
        ]);
        $result = $Validate->check($params);
        if (!$result) return self::apiAdminReturn(['msg' => $Validate->getError()]);


        if (!empty($file = request()->file('file_avatar'))) {
            $validate = ['size' => 1024 * 1024 * 3, 'ext' => 'jpg,png,gif,jpeg'];
            $dir = 'Upload/head_pic/';
            if (!($_exists = file_exists($dir))) $isMk = mkdir($dir);
            $parentDir = date('Ymd');
            $info = $file->validate($validate)->move($dir, true);
            if ($info) $params['avatar'] = '/' . $dir . $parentDir . '/' . $info->getFilename();
        }

        $update_data = [
            'username' => $params['username'],
            'avatar' => $params['avatar'],
            'wx_unqiue' => trim($params['wx_unqiue'] ?? ""),
            'alipay_unqiue' => trim($params['alipay_unqiue'] ?? ""),
        ];

        return (Admins::where('id', intval($params['user_info']['id']))->update($update_data)) ? self::apiAdminReturn(['msg' => '更新成功', 'status' => 1]) : self::apiAdminReturn(['msg' => '更新失败']);
    }

    /**
     * 检测交易密码是否正确
     * @param array $params
     */
    public static function checkPayPass($params = [])
    {
        $rule = [
            'paypwd' => [
                function ($value) use ($params) {
                    if (empty($value)) return false;
                    if (md5($value) != $params['user_info']['paypwd']) return false;
                    else return true;
                }
            ],
        ];
        $Validate = new Validate($rule, []);
        $result = $Validate->check($params);
        if (!$result) return false;
        else return true;
    }

    /**
     * [getIndexShowRec 首页展示的推荐会员]
     * @return [type] [description]
     */
    public static function getIndexShowRec(): array
    {
        return Users::where('is_check', 1)
            ->with(['userInfo' => function ($query) {
                $query = $query->hidden([
                    'user_sex', 'user_birth', 'auth_status', 'auth_mobile', 'auth_email', 'created_ip', 'browser_type', 'update_time', 'qq_basic', 'baidu_basic', 'github_basic',
                    'sina_basic', 'wx_basic', 'cnpscy_basic'
                ]);
            }])
            ->hidden(['password'])
            ->limit(6)
            ->select()
            ->toArray();
    }

    /**
     * [getAllUser 站内所有的会员]
     * @return [type] [description]
     */
    public static function getAllUser(): array
    {
        return Users::where('is_check', 1)
            ->with(['userInfo' => function ($query) {
                $query = $query->hidden([
                    'user_sex', 'user_birth', 'auth_status', 'auth_mobile', 'auth_email', 'created_ip', 'browser_type', 'update_time', 'qq_basic', 'baidu_basic', 'github_basic',
                    'sina_basic', 'wx_basic', 'cnpscy_basic'
                ]);
            }])
            ->hidden(['password'])
            ->select()
            ->toArray();
    }

    /**
     * [getWebShowDetail 站内会员详情]
     * @param  int|integer $user_id [description]
     * @return [type]               [description]
     */
    public static function getWebShowDetail(int $user_id = 0): array
    {
        if (empty($user_id)) return [];
        $data = Users::where([
            'is_check' => 1,
        ])->with(['userInfo' => function ($query) {
            $query = $query->hidden([
                'user_sex', 'user_birth', 'auth_status', 'auth_mobile', 'auth_email', 'created_ip', 'browser_type', 'update_time', 'qq_basic', 'baidu_basic', 'github_basic',
                'sina_basic', 'wx_basic', 'cnpscy_basic'
            ]);
        }])->hidden(['password'])
            ->find($user_id);
        if (empty($data)) return [];
        $data = $data->toArray();
        $data['user_info']['create_date'] = date('Y-m-d H:i:s', $data['user_info']['create_time']);
        return $data;
    }
}