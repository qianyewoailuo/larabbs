<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     * 供调用的命令
     *
     * @var string
     */
    protected $signature = 'larabbs:calculate-active-user';

    /**
     * The console command description.
     * 命令描述
     *
     * @var string
     */
    protected $description = '生成活跃用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 执行的方法
     *
     * @return mixed
     */
    public function handle(User $user)
    {
        // 在命令行打印一行信息
        $this->info('开始计算...');
        // trait中的获取活跃用户并缓存的方法
        $user->calculateAndCacheActiveUsers();
        // 执行完毕信息
        $this->info('成功生成!');
    }
}
