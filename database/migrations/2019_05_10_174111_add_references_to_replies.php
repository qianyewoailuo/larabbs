<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferencesToReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('replies', function (Blueprint $table) {
            // 当 user_id 对应的 users 表数据被删除的时候，删除词条
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            // 当 topic_id 对应的 topics 表数据被删除的时候，删除词条
            $table->foreign('topic_id')->references('id')
                ->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            Schema::table('replies', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['topic_id']);
            });
        });
    }
}
