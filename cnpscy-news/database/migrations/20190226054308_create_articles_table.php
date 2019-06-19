<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateArticlesTable extends Migrator
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
        $this->table('article_categorys', ['engine' => 'Innodb'])->setComment('文章分类表')
            ->setId('category_id')->setPrimaryKey('category_id')
            ->addColumn('category_name', 'string', ['limit' => 100, 'default' => '', 'comment' => '分类名称'])
            ->addColumn(Column::integer('parent_id')->setUnsigned()->setDefault(0)->setComment('父级Id'))
            ->addColumn(Column::integer('category_sort')->setUnsigned()->setDefault(0)->setComment('排序'))
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：删除；0：正常'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('parent_id')
            ->addIndex('is_check')
            ->addIndex('is_delete')
            ->create();

        $this->table('articles', ['engine' => 'Innodb'])->setComment('文章表')
            ->setId('article_id')->setPrimaryKey('article_id')
            ->addColumn(Column::integer('category_id')->setUnsigned()->setDefault(0)->setComment('商品分类'))
            ->addColumn('article_title', 'string', ['limit' => 200, 'default' => '', 'comment' => '文章标题'])
            ->addColumn('article_cover', 'string', ['limit' => 200, 'default' => '', 'comment' => '文章封面'])
            ->addColumn('article_keywords', 'string', ['limit' => 200, 'default' => '', 'comment' => 'head头部展示的关键字搜索'])
            ->addColumn('article_description', 'string', ['limit' => 200, 'default' => '', 'comment' => 'head头部展示的描述'])
            ->addColumn(Column::integer('article_sort')->setUnsigned()->setDefault(0)->setComment('排序[从小到大]'))
            ->addColumn('article_link', 'string', ['limit' => 200, 'default' => '', 'comment' => '详情外链'])
            ->addColumn('article_origin', 'string', ['limit' => 200, 'default' => '', 'comment' => '文章来源'])
            ->addColumn(Column::boolean('set_top')->setUnsigned()->setDefault(0)->setComment('置顶-后台设置'))
            ->addColumn(Column::boolean('is_recommend')->setUnsigned()->setDefault(0)->setComment('推荐-后台设置'))
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：删除；0：正常'))
            ->addColumn(Column::boolean('is_public')->setUnsigned()->setDefault(1)->setComment('公开度：0.私密；1.完全公开；2.密码访问'))
            ->addColumn('access_password', 'string', ['limit' => 60, 'default' => '', 'comment' => '对于公开度的“密码访问”设置的密码'])
            ->addColumn(Column::boolean('is_check')->setUnsigned()->setDefault(1)->setComment('是否可用：1：可用；0：禁用'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addColumn(Column::integer('read_num')->setUnsigned()->setDefault(0)->setComment('阅读数量'))
            ->addIndex('category_id')
            ->addIndex('is_delete')
            ->addIndex('is_public')
            ->addIndex('is_check')
            ->create();

        $this->table('article_datas', ['engine' => 'Innodb'])->setComment('文章内容表')
            ->addColumn(Column::integer('article_id')->setUnsigned()->setDefault(0)->setComment('文章主键'))
            ->addColumn(Column::text('article_content')->setComment('文章内容'))
            ->addColumn('create_ip', 'string', ['limit' => 20, 'default' => '', 'comment' => '创建时的IP'])
            ->addColumn('browser_type', 'string', ['limit' => 256, 'default' => '', 'comment' => '创建时浏览器类型'])
            ->addIndex('article_id')
            ->create();

        $this->table('article_labels', ['engine' => 'Innodb'])->setComment('文章标签表')
            ->setId('label_id')->setPrimaryKey('label_id')
            ->addColumn('label_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '标签名'])
            ->addColumn(Column::boolean('is_delete')->setUnsigned()->setDefault(0)->setComment('是否删除：1：删除；0：正常'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('is_delete')
            ->addIndex('create_time')
            ->create();

        $this->table('article_with_labels', ['engine' => 'Innodb'])->setComment('文章标签关联表')
            ->setId('with_id')->setPrimaryKey('with_id')
            ->addColumn(Column::integer('label_id')->setUnsigned()->setDefault(0)->setComment('文章标签Id'))
            ->addColumn(Column::integer('article_id')->setUnsigned()->setDefault(0)->setComment('文章Id'))
            ->addIndex('label_id')
            ->addIndex('article_id')
            ->create();

        $this->table('article_collections', ['engine' => 'Innodb'])->setComment('文章-收藏表')
            ->setId('collection_id')->setPrimaryKey('collection_id')
            ->addColumn(Column::integer('user_id')->setUnsigned()->setDefault(0)->setComment('好友Id'))
            ->addColumn(Column::integer('article_id')->setUnsigned()->setDefault(0)->setComment('文章Id'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('user_id')
            ->addIndex('article_id')
            ->create();

        $this->table('article_praises', ['engine' => 'Innodb'])->setComment('文章-点赞表')
            ->setId('praise_id')->setPrimaryKey('praise_id')
            ->addColumn(Column::integer('user_id')->setUnsigned()->setDefault(0)->setComment('好友Id'))
            ->addColumn(Column::integer('article_id')->setUnsigned()->setDefault(0)->setComment('文章Id'))
            ->addColumn(Column::integer('create_time')->setUnsigned()->setDefault(0)->setComment('创建时间'))
            ->addColumn(Column::integer('update_time')->setUnsigned()->setDefault(0)->setComment('更新时间'))
            ->addIndex('user_id')
            ->addIndex('article_id')
            ->create();
    }

    public function down()
    {
        $this->dropTable('article_categorys');
        $this->dropTable('articles');
        $this->dropTable('article_datas');
        $this->dropTable('article_labels');
        $this->dropTable('article_with_labels');
        $this->dropTable('article_collections');
        $this->dropTable('article_praises');
    }
}
