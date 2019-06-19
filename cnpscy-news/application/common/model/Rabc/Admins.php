<?php

namespace app\common\model\Rabc;

use app\common\model\Common;

class Admins extends Common
{
    public $pk = 'admin_id';
    public $is_delete = 0;//是否删除
    public $withModel = ['adminInfo'];//关联模型
    public $detailWithModel = ['roles'];

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['admin_name|admin_email', 'like', $params['search'] . '%'];
        return $params;
    }

    public static function init()
    {
        self::beforeInsert(function ($model) {
            $model->password = hash_make(empty($model->password) ? cnpscy_config('admin_default_pass') : $model->password);
        });

        self::afterInsert(function ($model) {
            $client_info = get_client_info();
            if (!$model->adminInfo()->save([
                $model->pk => $model->{$model->pk},
                'create_ip' => $client_info['ip'],
                'browser_type' => $client_info['agent'],
            ])) $model->delete();
        }, false);

        self::BeforeUpdate(function ($model) {
            if (empty($model->password)) unset($model->password);
            else $model->password = hash_make($model->password);
        });

        self::afterUpdate(function ($model) {
            $model->adminInfo()->update([$model->pk => $model->{$model->pk}]);


            /**
             * 角色
             */
            $role_ids = input('role_id', []);

            $all_roles = Roles::where([
                'is_check' => 1,
                'is_delete' => 0
            ])->all(implode(',', explode(',', $role_ids)))->toArray();//当前选中的菜单权限
            $new_all_roles = array_column($all_roles, 'role_id', 'role_id');
            $has_roles = $model->roles->toArray();//当前已有的菜单权限
            $new_has_roles = array_column($has_roles, 'role_id', 'role_id');

            /**
             * 添加的权限
             */
            $add_roles = get_array_diff($new_all_roles, $new_has_roles);
            if (!empty($add_roles)) {
                foreach ($add_roles as $roles) $model->grantRoles([
                    $model->pk => $model->{$model->pk},
                    'role_id' => $roles,
                ]);
            }

            /**
             * 要删除的角色
             */
            $delete_roles = get_array_diff($new_has_roles, $new_all_roles);
            if (!empty($delete_roles)) {
                foreach ($delete_roles as $roles) $model->deleteRoles([
                    $model->pk => $model->{$model->pk},
                    'role_id' => $roles,
                ]);
            }
        });
    }

    public function adminInfo()
    {
        return $this->hasOne(AdminInfos::class, $this->pk, $this->pk);
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'admin_roles', 'role_id', $this->pk);
    }

    public function grantRoles($roles)
    {
        return AdminRoles::create($roles);
    }

    public function deleteRoles($roles)
    {
        return AdminRoles::where($roles)->delete();
    }
}
