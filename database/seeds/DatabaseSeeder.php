<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 注册填充数据
        $this->call(UsersTableSeeder::class);
		$this->call(TopicsTableSeeder::class);
    }
}
