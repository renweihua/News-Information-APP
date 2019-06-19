<?php

namespace app\common\model;

use app\common\traits\CommonController;
use think\exception\PDOException;
use think\Model;

class Common extends Model
{
    use CommonController;

    public $pk = 'id';//主键

    protected $autoWriteTimestamp = false;// 开启自动写入时间戳

    public $is_delete = 1;//是否删除
    public $delete_field = 'is_delete';//删除字段

    public $withModel = [],//关联模型
        $detailWithModel = [];//详情的模型

    public $withAttr = [];//动态获取器

    private function getFilterRequest($request_data = [])
    {
        $request_data['search'] = trim($request_data['search'] ?? '');
        $request_data['order'] = !empty($request_data['order']) ? [$this->pk => $request_data['order']] : [$this->pk => 'DESC'];
        $request_data['limit'] = intval($request_data['limit'] ?? 10);
        if ($request_data['limit'] == 0 || !is_numeric($request_data['limit'])) $request_data['limit'] = 10;
        $request_data['where'] = [];
        return $request_data;
    }

    public function setWhereFilter(array $params = [])
    {
        if (isset($params['is_check']) && intval($params['is_check']) != -1) $params['where'][] = ['is_check', 'eq', intval($params['is_check'])];
        if (isset($params['is_public']) && intval($params['is_public']) != -1) $params['where'][] = ['is_public', 'eq', intval($params['is_public'])];

        if (isset($params['parent_id']) && intval($params['parent_id']) != -1) $params['where'][] = ['parent_id', 'eq', intval($params['parent_id'])];
        return $params;
    }

    /**
     * 筛选列表
     * @param $request_data
     * @param $this_model
     * @return mixed
     */
    public function getListFilter($request_data = [])
    {
        $request_data = $this->getFilterRequest($request_data);//对于参数的过滤设置

        //Search搜索
        if (method_exists($this, 'setSearchWhereFilter')) $request_data = $this->setSearchWhereFilter($request_data);
        //是否存在筛选列表
        if (method_exists($this, 'setWhereFilter')) $request_data = $this->setWhereFilter($request_data);


        //是否删除的检测
        $model = $this->where(function ($query) {
            if (intval($this->is_delete) == 1 && !empty($this->delete_field)) $query = $query->where($this->delete_field, 0);
        });

        $data_list = $model->where($request_data['where'])->with($this->withModel)->order($request_data['order'])->paginate($request_data['limit'])->toArray();

        // return  $this->ajaxReturn([
        //     'cur_page' => intval($data_list['current_page'] ?? 0),
        //     'page_size' => intval($data_list['per_page'] ?? $request_data['limit']),
        //     'count_page' => intval($data_list['last_page'] ?? 0),
        //     'total' => intval($data_list['total'] ?? 0),
        //     'rows' => $data_list['data'] ?? []
        // ]);

        return [
            'cur_page' => intval($data_list['current_page'] ?? 0),
            'page_size' => intval($data_list['per_page'] ?? $request_data['limit']),
            'count_page' => intval($data_list['last_page'] ?? 0),
            'count' => intval($data_list['total'] ?? 0),
            'data' => $data_list['data'] ?? [],
        ];
    }

    private function ajaxReturn($data = [])
    {
        return json($data);
    }

    public static function old_getListFilter($request_data = [], $this_model)
    {
        //搜索条件筛选
        $query = $this_model::where($this_model::$operationWhere);
        if (!empty($getSearchField = $this_model::$getSearchField) && !empty($request_data['search_name'])) {
            foreach ($getSearchField as $key => $filed) {
                ($key == 0) ? $query = $query->where($filed, 'like', trim($request_data['search_name']) . '%') : $query = $query->orWhere($filed, 'like', trim($request_data['search_name']) . '%');
            }
        }

        /**
         * 是否进行字段筛选
         * 0.字段名
         * 1.字段类型
         * 2.字段默认值
         * 3.获取的值与默认值是否允许相同筛选
         */
        if (!empty($getListFilterWhere = $this_model::$getListFilterWhere)) {
            foreach ($getListFilterWhere as $key => $value) {
                if (empty($value)) continue;
                if (!isset($request_data[$value[0]]) && !isset($value[2])) continue;
                $_field = $request_data[$value[0]] ?? $value[2];
                if ($_field == $value[2] && empty($value[3])) continue;
                if (isset($value[3]) && isset($value[2])) {
                    if (isset($value[2]) != $_field) $query = $query->where($value[0], set_field_filtering($_field, $value[1], $value[2]));
                    else {
                        $_field = ($_field ?? $value[2]);
                        $query = $query->where($value[0], set_field_filtering($_field, $value[1], $value[2]));
                    }
                } else $query = $query->where($value[0], set_field_filtering($_field, $value[1]));
            }
        }

        //是否关联模型查询、关联模型指定的字段查询操作 --- 排序
        $data_list = $query->with((array)($this_model::$getRelationWith))->order($this_model::$getOrderByList)->paginate(10)->toArray();

        return [
            'cur_page' => intval($data_list['current_page'] ?? 0),
            'page_size' => intval($data_list['per_page'] ?? $request_data['limit']),
            'count_page' => intval($data_list['last_page'] ?? 0),
            'count' => intval($data_list['total'] ?? 0),
            'data' => $data_list['data'] ?? []
        ];;
    }

    public function getDetail($id)
    {
        return $this->where(function ($query) {
                if (intval($this->is_delete) == 1 && !empty($this->delete_field)) $query = $query->where($this->delete_field, 0);
            })->with($this->detailWithModel ?? $this->withModel)->find($id);
    }

    public function getCounts($where)
    {
        return $this->where($where)->count();
    }

    public function dataInsert($param = [])
    {
        try {
            if ($primary = $this->allowField(true)->save($param)) return ['status' => 1, 'msg' => '插入成功！', 'data' => $primary];
            else return ['status' => 0, 'msg' => '插入失败！'];
        } catch (PDOException $e) {
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function dataUpdate($request_data = [])
    {
        try {
            if ($primary = $this->allowField(true)->save($request_data, [$this->pk => $request_data[$this->pk]])) return ['status' => 1, 'msg' => '更新成功！', 'data' => $primary];
            else return ['status' => 0, 'msg' => '更新失败！'];
        } catch (PDOException $e) {
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function dataDelete($request_data = [])
    {
        if (empty($request_data[$this->pk])) return ['msg' => '主键为必填项！'];

        $alert_prefix = '{ ' . $this->pk . ' => ' . $request_data[$this->pk] . ' }';
        if (empty($this_model = $this->where(($this->is_delete || !empty($this->delete_field)) ? [$this->delete_field => 0] : [])->find($request_data[$this->pk]))) return ['msg' => $alert_prefix . '不存在！'];

        switch (intval($this->is_delete)) {
            case 0:
                $operating_state = $this->where([$this->pk => $request_data[$this->pk]])->update([$this->delete_field => 1]);
                break;
            case 1:
                $operating_state = $this->where([$this->pk => $request_data[$this->pk]])->delete();
                break;
        }
        if ($operating_state) return ['msg' => $alert_prefix . '删除成功！', 'status' => 1];
        else return $return = ['msg' => $alert_prefix . '删除失败！'];
    }

    public function changeFiledStatus($request_data = [])
    {
        if (empty($request_data[$this->pk])) return ['msg' => '主键为必填项！'];

        $alert_prefix = '{ ' . $this->pk . ' => ' . $request_data[$this->pk] . ' }';
        if (empty($this_model = $this->find($request_data[$this->pk]))) return ['msg' => $alert_prefix . '不存在！'];

        if ($this->where([$this->pk => $request_data[$this->pk]])->update([$request_data['change_field'] => $request_data['change_value']])) return ['msg' => $alert_prefix . '设置成功！', 'status' => 1];
        else return $return = ['msg' => $alert_prefix . '设置失败！'];
    }

}