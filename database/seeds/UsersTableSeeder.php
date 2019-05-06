<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        // 头像假数据
        $avatars = [
            //  花丸幼稚园头像
            'https://s2.ax1x.com/2019/05/05/E04Mb6.jpg',
            'https://s2.ax1x.com/2019/05/05/E04lVK.md.jpg',
            'https://s2.ax1x.com/2019/05/05/E041UO.md.jpg',
            'https://s2.ax1x.com/2019/05/05/E04uK1.jpg',
            'https://s2.ax1x.com/2019/05/05/E04KDx.md.jpg',

            // 中世纪怪物头像
            'https://s2.ax1x.com/2019/05/05/E0TbtK.png',
            'https://s2.ax1x.com/2019/05/05/E0TqfO.png',
            'https://s2.ax1x.com/2019/05/05/E0TX1e.png',
            'https://s2.ax1x.com/2019/05/05/E0TznA.png',
            'https://s2.ax1x.com/2019/05/05/E0THk6.png',
            'https://s2.ax1x.com/2019/05/05/E0TOpD.png',
            'https://s2.ax1x.com/2019/05/05/E0Tj6H.png',
            'https://s2.ax1x.com/2019/05/05/E07S0I.png',
            'https://s2.ax1x.com/2019/05/05/E07p7t.png',
        ];

        // factory(User::class) 根据指定的 User 生成模型工厂构造器，对应加载 UserFactory.php 中的工厂设置。
        $users = factory(User::class)
            // 生成 20 个用户数据
            ->times(10)
            // 生成集合对象
            ->make()
            // 是 集合对象 提供的 方法，用来迭代集合中的内容并将其传递到回调函数中
            // use 是 PHP 中匿名函数提供的本地变量传递机制，匿名函数中必须通过 use 声明的引用，才能使用本地变量。
            ->each(function ($user, $index)
            use ($faker, $avatars) {
                // 从头像数组中随机取出一个并赋值
                $user->avatar = $faker->randomElement($avatars);
            });

        // 让隐藏字段可见，确保入库时数据库不会报错,并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'qianyewoailuo';
        $user->password = bcrypt('luo12345');
        $user->email = 'qianyewoailuo@126.com';
        $user->avatar = 'https://s2.ax1x.com/2019/05/05/EwxTl6.png';
        $user->save();
    }
}
