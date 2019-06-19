<?php

namespace app\admin\controller;

use app\common\traits\CommonController;
use think\Controller;
use think\Request;

class Common extends Controller
{
    use CommonController;

    public $model = '';
    public $requestValidator;

    /**
     * 布局模板
     * @var string
     */
    public $layout = 'default';

    public function initialize()
    {
        date_default_timezone_set(cnpscy_config('system_timezone'));//设置时区

        $this->isLogin();

        // 如果有使用模板布局
        if (!empty($this->layout)) {
            //$this->view->engine->layout('layout/' . $this->layout);
        }

        $this->checkRabc();
    }

    public function index($extends_info = [])
    {
        if (request()->isPost()) return self::apiAdminReturn(
            array_merge(['data' => $this->model->getListFilter(input()), 'status' => 1], $extends_info ?? [])
        );
        else return $this->fetch();
    }

    public function detail()
    {
        if (request()->isPost()) return self::apiAdminReturn(['data' => $this->model->getDetail(input($this->model->pk, 0))]);
        else return $this->fetch();
    }

    public function update(Request $request)
    {
        $request_data = $request->param();
        if (!empty($request_data[$this->model->pk])) {
            if (empty($request_data[$this->model->pk])) $this->save($request_data);

            if (!empty($this->requestValidator) && !empty($this->requestValidator . '.edit')) {
                $result = $this->validate($request_data, $this->requestValidator . '.edit');
                if ($result !== true) return self::apiAdminReturn(['msg' => $result]);
            }

            return self::apiAdminReturn($this->model->dataUpdate($request_data));
        } else {
            if (!empty($this->requestValidator) && !empty($this->requestValidator . '.add')) {
                $result = $this->validate($request_data, $this->requestValidator . '.add');
                if ($result !== true) return self::apiAdminReturn(['msg' => $result]);
            }
            return self::apiAdminReturn($this->model->dataInsert($request_data));
        }
    }

    public function delete()
    {
        if (request()->isPost()) return self::apiAdminReturn($this->model->dataDelete(input()));
    }

    public function changeFiledStatus(Request $request)
    {
        if (request()->isPost()) {
            $request_data = $request->param();
            return self::apiAdminReturn($this->model->changeFiledStatus($request_data));
        }
    }

    /**
     * [_empty 空方法操作]
     * @param    [string]      $name [方法名称]
     */
    public function _empty($name)
    {
        if (\request()->isAjax()) {
            return self::apiAdminReturn(['msg' => $name . ' 非法访问']);
        } else {
            exit($name . ' 非法访问');
        }
    }

    /**
     * [IsLogin 登录校验]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:42:35+0800
     */
    protected function isLogin()
    {
        if (empty(session(cnpscy_config('admin_info_session_unique')))) {
            if (\request()->isAjax()) {
                exit(json_encode(DataReturn('登录失效，请重新登录', -400)));
            } else {
                $this->redirect(url('/admin/logins'))->remember();
                exit;
            }
        }
    }

    /**
     * 进行权限检测认证
     */
    private function checkRabc()
    {
        if (session(cnpscy_config('admin_info_session_unique') . '.admin_id') == 1) return;
        $module_name = lcfirst(request()->module());
        $controller_name = lcfirst(request()->controller());
        $action_name = lcfirst(request()->action());
        $this_rabc = $controller_name . '/' . $action_name;
        if ($controller_name . '/' . $action_name == 'indexs/index') return;
        $rabc_list = session(cnpscy_config('admin_rabc_session_unique'));
        if (array_key_exists($this_rabc, $rabc_list[session(cnpscy_config('admin_info_session_unique') . '.use_role')] ?? [])) return;
        else{
            if (\request()->isAjax()) {
                return self::apiAdminReturn(['msg' => '无权限']);
            } else {
                return $this->error('无权限');
            }
        }
    }
}