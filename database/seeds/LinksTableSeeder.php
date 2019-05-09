<?php

use Illuminate\Database\Seeder;
use App\Models\Link;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成数据集合
        // $links = factory(Link::class)->times(6)->make();

        $data = [
            [
                'title' => 'Laravel 5.8 中文文档',
                'link'  =>  'https://learnku.com/docs/laravel/5.8',
            ],
            [
                'title' =>  'Laravel 项目开发规范',
                'link'  =>  'https://learnku.com/docs/laravel-specification/5.5',
            ],
            [
                'title' =>  'Laravel 速查表',
                'link'  =>  'https://learnku.com/docs/laravel-cheatsheet/5.8',
            ],
            [
                'title' =>  'Laravel 之道',
                'link'  =>  'https://learnku.com/docs/the-laravel-way/5.6',
            ],
            [
                'title' =>  'Laravel Mix 中文文档',
                'link'  =>  'https://learnku.com/docs/laravel-mix/4.0',
            ],
            [
                'title' =>  'Vue 2 入门学习笔记',
                'link'  =>  'https://learnku.com/docs/learn-vue2',
            ],
        ];

        // 将数据集合转换为数组并插入到数据库中
        Link::insert($data);
    }
}
