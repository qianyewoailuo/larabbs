<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvatarAndIntroductionToUsersTable extends Migration
{
    /**
     * Run the migrations.
     * 执行迁移
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 增加头像 个人简介字段
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('introduction')->nullable()->comment('个人简介');
        });
    }

    /**
     * Reverse the migrations.
     * 回滚迁移
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // 删除字段
            $table->dropColumn('avatar');
            $table->dropColumn('introduction');
        });
    }
}
