<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateApiLogsTable extends Migrator
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
        $this->table('api_admin_logs', ['engine' => 'Innodb'])->setComment('后台API请求日志记录表')
            ->setId('log_id')->setPrimaryKey('log_id')
            ->addColumn(Column::integer('admin_id')->setUnsigned()->setDefault(0)->setComment('管理员主键'))
            ->addColumn(Column::string('log_description', 256)->setDefault('')->setComment('描述'))
            ->addColumn(Column::string('request_url', 256)->setDefault('')->setComment('请求URL'))
            ->addColumn(Column::string('log_action', 100)->setDefault('')->setComment('请求方法'))
            ->addColumn(Column::string('log_method', 20)->setDefault('')->setComment('请求类型/请求方式'))
            ->addColumn(Column::string('create_ip', 20)->setDefault('')->setComment('请求IP'))
            ->addColumn(Column::string('browser_type', 256)->setDefault('')->setComment('请求时浏览器类型'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：是；0：否'))
            ->addColumn(Column::boolean('is_ok')->setUnsigned()->setDefault(1)->setComment('请求状态：1：成功；0：失败'))
            ->addColumn(Column::text('request_data')->setComment('请求参数'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('admin_id')
            ->addIndex('is_delete')
            ->create();

        $this->table('api_web_logs', ['engine' => 'Innodb'])->setComment('后台API请求日志记录表')
            ->setId('log_id')->setPrimaryKey('log_id')
            ->addColumn(Column::integer('user_id')->setUnsigned()->setDefault(0)->setComment('会员主键'))
            ->addColumn(Column::string('log_description', 256)->setDefault('')->setComment('描述'))
            ->addColumn(Column::string('request_url', 256)->setDefault('')->setComment('请求URL'))
            ->addColumn(Column::string('log_action', 100)->setDefault('')->setComment('请求方法'))
            ->addColumn(Column::string('log_method', 20)->setDefault('')->setComment('请求类型/请求方式'))
            ->addColumn(Column::string('create_ip', 20)->setDefault('')->setComment('请求IP'))
            ->addColumn(Column::string('browser_type', 256)->setDefault('')->setComment('请求时浏览器类型'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：是；0：否'))
            ->addColumn(Column::boolean('is_ok')->setUnsigned()->setDefault(1)->setComment('请求状态：1：成功；0：失败'))
            ->addColumn(Column::text('request_data')->setComment('请求参数'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('user_id')
            ->addIndex('is_delete')
            ->create();
    }

    public function down()
    {
        $this->dropTable('api_admin_logs');
        $this->dropTable('api_web_logs');
    }
}
