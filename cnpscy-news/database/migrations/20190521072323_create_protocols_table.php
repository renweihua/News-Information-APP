<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateProtocolsTable extends Migrator
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
        $this->table('protocols', ['engine' => 'Innodb'])->setComment('协议表')
            ->setId('protocol_id')->setPrimaryKey('protocol_id')
            ->addColumn(Column::smallInteger('protocol_type')->setUnsigned()->setDefault(0)->setComment('协议类型：common_protocol_type_list'))
            ->addColumn(Column::text('content')->setComment('协议内容'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除（0.未删除；1.已删除）'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('protocol_type')
            ->addIndex('is_delete')
            ->create();
    }

    public function down()
    {
        $this->dropTable('protocols');
    }
}
