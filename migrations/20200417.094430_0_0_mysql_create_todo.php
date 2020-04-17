<?php

namespace App\Migration;

use Spiral\Migrations\Migration;

class OrmMysql465ee1014492b1fc83889e661f847c53 extends Migration
{
    protected const DATABASE = 'mysql';

    public function up()
    {
        $this->table('todo')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('title', 'string', [
                'nullable' => false,
                'default'  => '',
                'size'     => 255
            ])
            ->addColumn('content', 'text', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('status', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('updated_at', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('published_at', 'datetime', [
                'nullable' => true,
                'default'  => null
            ])
            ->addColumn('deleted_at', 'datetime', [
                'nullable' => true,
                'default'  => null
            ])
            ->addColumn('user_id', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addIndex(["user_id"], [
                'name'   => 'todo_index_user_id_5e99504e52c7c',
                'unique' => false
            ])
            ->addIndex(["published_at"], [
                'name'   => 'todo_index_published_at_5e99504e530bc',
                'unique' => false
            ])
            ->addForeignKey(["user_id"], 'user', ["id"], [
                'name'   => 'todo_foreign_user_id_5e99504e52c8d',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('todo')->drop();
    }
}
