<?php

namespace app\common\model\Rabc;

use app\common\model\Common;

class Roles extends Common
{
    public $pk = 'role_id';
    public $is_delete = 0;
    public $detailWithModel = ['menus'];

    public function setSearchWhereFilter(array $params = [])
    {
        if (isset($params['search']) && !empty($params['search'])) $params['where'][] = ['role_name', 'like', $params['search'] . '%'];

        if (isset($params['is_check']) && intval($params['is_check'] ?? -1) != -1) $params['where'][] = ['is_check', '=', intval($params['is_check'] ?? -1)];

        return $params;
    }

    public static function init()
    {
        self::afterUpdate(function ($model) {
            $menu_rules = input('menu_rules', []);

            $all_menus = AdminMenus::all(implode(',', $menu_rules))->toArray();//当前选中的菜单权限
            $new_all_menus = array_column($all_menus, 'menu_id', 'menu_id');
            $has_menus = $model->menus->toArray();//当前已有的菜单权限
            $new_has_menus = array_column($has_menus, 'menu_id', 'menu_id');

            /**
             * 添加的权限
             */
            $add_menus = get_array_diff($new_all_menus, $new_has_menus);
            if (!empty($add_menus)) {
                foreach ($add_menus as $menus) $model->grantMenus([
                    $model->pk => $model->{$model->pk},
                    'menu_id' => $menus,
                ]);
            }

            /**
             * 要删除的权限
             */
            $delete_menus = get_array_diff($new_has_menus, $new_all_menus);
            if (!empty($delete_menus)) {
                foreach ($delete_menus as $menus) $model->deleteMenus([
                    $model->pk => $model->{$model->pk},
                    'menu_id' => $menus,
                ]);
            }
        });
    }

    public function menus()
    {
        return $this->belongsToMany(AdminMenus::class, 'role_menus', 'menu_id', $this->pk)->where(['is_delete' => 0, 'check_auth' => 1, 'is_check' => 1]);
    }

    /**
     * @Function grantMenus
     * @author: cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:给角色赋予权限
     * @englishAnnotation:
     * @param $menus
     * @return bool
     */
    public function grantMenus($menus)
    {
        return RoleMenus::create($menus);
    }

    /**
     * @Function deleteMenus
     * @author: cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:取消角色赋予的权限
     * @englishAnnotation:
     * @param $menus
     * @return mixed
     */
    public function deleteMenus($menus)
    {
        return RoleMenus::where($menus)->delete();
    }
}
