<?php


use Phinx\Migration\AbstractMigration;

class CreateEventlogTable extends AbstractMigration
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
        $table = $this->table('event_log', ['id' => false, 'primary_key' => ['id'], 'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci']);

        //添加数据字段
        $table->addColumn('id', 'integer', ['identity' => true, 'signed' => false, 'limit' => 11, 'comment' => '事件记录ID'])
            ->addColumn('operate', 'string', ['default' => '', 'limit' => 128, 'comment' => '操作类型'])
            ->addColumn('type', 'enum', ['values' => 'built_in,custom', 'default' => 'built_in', 'comment' => 'Event类型'])
            ->addColumn('table', 'char', ['default' => '', 'limit' => 36, 'comment' => '表名'])
            ->addColumn('project_id', 'integer', ['signed' => false, 'default' => 0, 'limit' => 11, 'comment' => '项目ID'])
            ->addColumn('primary_id', 'integer', ['signed' => false, 'default' => 0, 'limit' => 11, 'comment' => '主键ID'])
            ->addColumn('record', 'json', ['null' => true, 'comment' => '记录数据'])
            ->addColumn('from', 'string', ['default' => '', 'limit' => 64, 'comment' => '来自哪里'])
            ->addColumn('created_by', 'integer', ['signed' => false, 'default' => 0, 'limit' => 11, 'comment' => '创建者'])
            ->addColumn('created', 'integer', ['signed' => false, 'default' => 0, 'limit' => 11, 'comment' => '创建时间'])
            ->addColumn('uuid', 'char', ['default' => '', 'limit' => 36, 'comment' => '全局唯一标识符']);

        //添加索引
        $table->addIndex(['project_id'], ['type' => 'normal', 'name' => 'idx_project_id']);

        //执行创建
        $table->create();
    }
}
