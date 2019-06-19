<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateAdminsTable extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $this->table('admins', ['engine' => 'Innodb'])->setComment('管理员认证表')
            ->setId('admin_id')->setPrimaryKey('admin_id')
            ->addColumn('admin_name', 'string', ['limit' => 100, 'default' => '', 'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 60, 'default' => password_hash('123456', PASSWORD_DEFAULT), 'comment' => '密码'])
            ->addColumn('admin_head', 'string', ['limit' => 200, 'default' => '', 'comment' => '头像'])
            ->addColumn('admin_email', 'string', ['limit' => 100, 'default' => '', 'comment' => '邮箱'])
            ->addColumn('login_token', 'string', ['limit' => 60, 'default' => '', 'comment' => 'login_token'])
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('登陆状态[0.尚未开放；1.正常；2.禁用]'))
            ->addColumn(Column::boolean('kick_out')->setUnsigned()->setDefault(2)->setComment('是否踢出登录[0：表示在线；1：踢出登录；2.未登录]'))
            ->addColumn(Column::integer('use_role')->setUnsigned()->setDefault(0)->setComment('正在使用的角色Id'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：是；0：否'))
            ->addIndex('is_check')
            ->addIndex('kick_out')
            ->addIndex('is_delete')
            ->create();

        $this->table('admin_infos', ['engine' => 'Innodb'])->setComment('管理员信息表')
            ->setId(false)
            ->addColumn(Column::integer('admin_id')->setUnsigned()->setDefault(0)->setComment('管理员Id'))
            ->addColumn(Column::integer('login_num')->setUnsigned()->setDefault(0)->setComment('登录次数'))
            ->addColumn('created_ip', 'string', ['limit' => 20, 'default' => '', 'comment' => '创建时的IP'])
            ->addColumn('browser_type', 'string', ['limit' => 200, 'default' => '', 'comment' => '创建时浏览器类型'])
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('admin_id')
            ->addIndex('create_time')
            ->create();

        $this->table('admin_login_logs', ['engine' => 'Innodb'])->setComment('管理员登录日志表')
            ->setId('log_id')->setPrimaryKey('log_id')
            ->addColumn(Column::integer('admin_id')->setUnsigned()->setDefault(0)->setComment('管理员Id'))
            ->addColumn(Column::string('created_ip', 20)->setDefault('')->setComment('创建时的IP'))
            ->addColumn(Column::string('browser_type', 256)->setDefault('')->setComment('创建时浏览器类型'))
            ->addColumn(Column::boolean('is_ok')->setUnsigned()->setDefault(1)->setComment('登录状态：1：成功；0：失败'))
            ->addColumn(Column::string('description', 256)->setDefault('')->setComment('描述'))
            ->addColumn(Column::string('log_action', 100)->setDefault('')->setComment('请求方法'))
            ->addColumn(Column::string('log_method', 20)->setDefault('')->setComment('请求类型/请求方式'))
            ->addColumn(Column::string('request_data', 256)->setDefault('')->setComment('请求参数'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：是；0：否'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('admin_id')
            ->addIndex('is_ok')
            ->addIndex('is_delete')
            ->create();

        $this->table('admin_menus', ['engine' => 'Innodb'])->setComment('后台-菜单表')
            ->setId('menu_id')->setPrimaryKey('menu_id')
            ->addColumn(Column::integer('parent_id')->setUnsigned()->setDefault(0)->setComment('父级id'))
            ->addColumn('menu_name', 'string', ['limit' => 256, 'default' => '', 'comment' => '菜单名称'])
            ->addColumn('menu_url', 'string', ['limit' => 256, 'default' => 'javascript:;', 'comment' => '控制器/方法'])
            ->addColumn('api_url', 'string', ['limit' => 200, 'default' => '', 'comment' => '接口地址'])
            ->addColumn('menu_icon', 'string', ['limit' => 100, 'default' => '', 'comment' => '图标'])
            ->addColumn(Column::integer('menu_sort')->setUnsigned()->setDefault(0)->setComment('排序[从小到大]'))
            ->addColumn(Column::boolean('check_auth')->setUnsigned()->setDefault(1)->setComment('是否检测权限【0.不检测；1.检测】'))
            ->addColumn(Column::boolean('is_left')->setUnsigned()->setDefault(0)->setComment('是否展示左侧：1：展示；0：隐藏'))
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：删除；0：正常'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('check_auth')
            ->addIndex('is_left')
            ->addIndex('is_check')
            ->addIndex('is_delete')
            ->create();

        $this->table('roles', ['engine' => 'Innodb'])->setComment('后台-角色表')
            ->setId('role_id')->setPrimaryKey('role_id')
            ->addColumn('role_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '角色名称'])
            ->addColumn('role_remarks', 'string', ['limit' => 200, 'default' => '', 'comment' => '备注'])
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：删除；0：正常'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('is_check')
            ->addIndex('is_delete')
            ->create();

        $this->table('role_menus', ['engine' => 'Innodb'])->setComment('后台-角色权限表')
            ->setId('with_id')->setPrimaryKey('with_id')
            ->addColumn(Column::integer('role_id')->setUnsigned()->setDefault(0)->setComment('角色Id'))
            ->addColumn(Column::integer('menu_id')->setUnsigned()->setDefault(0)->setComment('菜单Id'))
            ->addIndex('role_id')
            ->addIndex('menu_id')
            ->create();

        $this->table('admin_roles', ['engine' => 'Innodb'])->setComment('后台-角色与管理员关联表')
            ->setId('with_id')->setPrimaryKey('with_id')
            ->addColumn(Column::integer('role_id')->setUnsigned()->setDefault(0)->setComment('角色Id'))
            ->addColumn(Column::integer('admin_id')->setUnsigned()->setDefault(0)->setComment('管理员Id'))
            ->addIndex('role_id')
            ->addIndex('admin_id')
            ->create();
    }

    public function down()
    {
        $this->dropTable('admins');
        $this->dropTable('admin_infos');
        $this->dropTable('admin_login_logs');
        $this->dropTable('adminmenus');
        $this->dropTable('admin_roles');
        $this->dropTable('role_menus');
        $this->dropTable('admin_roles');
    }
}
