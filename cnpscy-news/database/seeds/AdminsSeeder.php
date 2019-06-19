<?php

use think\migration\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data[] = ['admin_name' => 'admin'];
        \app\common\model\Rabc\Admins::insertAll($data);
    }
}