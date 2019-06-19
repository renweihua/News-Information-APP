<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateConfigsTable extends Migrator
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
        $this->table('configs')->setComment('网站配置信息表')//, ['engine'=>'Innodb']
        ->setId('config_id')->setPrimaryKey('config_id')
            ->addColumn('config_title', 'string', ['limit' => 200, 'default' => '', 'comment' => '标题'])
            ->addColumn('config_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '参数名称'])
            ->addColumn('config_value', 'string', ['limit' => 200, 'default' => '', 'comment' => '参数值'])
            ->addColumn('config_extra', 'string', ['limit' => 200, 'default' => '', 'comment' => '配置项'])
            ->addColumn('config_remark', 'string', ['limit' => 200, 'default' => '', 'comment' => '说明'])
            ->addColumn(Column::smallInteger('config_group')->setUnsigned()->setDefault(0)->setComment('分组'))
            ->addColumn(Column::smallInteger('config_type')->setUnsigned()->setDefault(0)->setComment('类型：0.字符串；1.数字；2.文本；3.select下拉框；4.图片；5.富文本'))
            ->addColumn(Column::smallInteger('config_sort')->setUnsigned()->setDefault(0)->setComment('排序[从小到大]'))
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：是；0：否'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('config_group')
            ->addIndex('is_check')
            ->addIndex('is_delete')
            ->create();
    }

    public function down()
    {
        $this->dropTable('configs');
    }
}
