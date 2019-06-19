<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateFriendlinksTable extends Migrator
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
        $this->table('friendlinks')->setComment('友情链接表')//, ['engine'=>'Innodb']
        ->setId('link_id')->setPrimaryKey('link_id')
            ->addColumn('link_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '名称'])
            ->addColumn('link_url', 'string', ['limit' => 200, 'default' => 'javascript:;', 'comment' => '链接URL'])
            ->addColumn('link_img', 'string', ['limit' => 200, 'default' => '', 'comment' => '链接图标'])
            ->addColumn(Column::smallInteger('link_sort')->setUnsigned()->setDefault(0)->setComment('排序[从小到大]'))
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::boolean('open_window')->setUnsigned()->setDefault(0)->setComment('是否打开新窗口：1：是；0：否'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('is_check')
            ->addIndex('link_sort')
            ->create();
    }

    public function down()
    {
        $this->dropTable('friendlinks');
    }
}
