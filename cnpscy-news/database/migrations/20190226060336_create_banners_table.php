<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateBannersTable extends Migrator
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
        $this->table('banners', ['engine' => 'Innodb'])->setComment('banner表')
            ->setId('banner_id')->setPrimaryKey('banner_id')
            ->addColumn('banner_title', 'string', ['limit' => 200, 'default' => '', 'comment' => '标题'])
            ->addColumn(Column::smallInteger('banner_type')->setUnsigned()->setDefault(0)->setComment('Banner类型：common_banner_type_list'))
            ->addColumn('banner_cover', 'string', ['limit' => 200, 'default' => '', 'comment' => '封面'])
            ->addColumn('banner_link', 'string', ['limit' => 200, 'default' => '', 'comment' => '外链'])
            ->addColumn('banner_words', 'string', ['limit' => 200, 'default' => '', 'comment' => '文字描述'])
            ->addColumn(Column::smallInteger('banner_sort')->setUnsigned()->setDefault(0)->setComment('排序[从小到大]'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：删除；0：正常'))
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('banner_type')
            ->addIndex('is_delete')
            ->addIndex('is_check')
            ->create();
    }

    public function down()
    {
        $this->dropTable('banners');
    }
}
