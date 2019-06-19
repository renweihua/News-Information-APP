<?php

use think\migration\Seeder;

class ArticlesSeeder extends Seeder
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
        $factory = \Faker\Factory::create();
        $time = time();

        for ($i = 0; $i < 5; $i++){
            $unixTime = $factory->unixTime;
            $category_data[] = [
                'category_name' => $factory->unique()->userName,
                'category_sort' => mt_rand(1, 100000),
                'is_check' => 1,
                'create_time' => $time,
                'update_time' => $time,
            ];
        }
        \app\common\model\Article\ArticleCategorys::insertAll($category_data);

        $categorys_num = \app\common\model\Article\ArticleCategorys::where('is_check', 1)->count();


        for ($i = 0; $i < 1000; $i++){
            $unixTime = $factory->unixTime;
            $data[] = [
                'article_title' => $factory->unique()->userName,
                'category_id' => mt_rand(1, $categorys_num),
                'article_cover' => cnpscy_config('site_web_logo'),
                'article_keywords' => mt_rand(0, 1),
                'article_description' => rand(10, 1000),
                'article_sort' => mt_rand(1, 100000),
                'set_top' => mt_rand(0, 1),
                'is_recommend' => mt_rand(0, 1),
                'is_public' => 1,
                'is_check' => 1,
                'read_num' => mt_rand(0, 1000000),
                'create_time' => $time,
                'update_time' => $time,
            ];
        }
        \app\common\model\Article\Articles::insertAll($data);
    }
}