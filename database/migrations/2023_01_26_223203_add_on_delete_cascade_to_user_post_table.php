<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_post', function (Blueprint $table) {
            $table->dropForeign('user_post_post_id_foreign');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->dropForeign('user_post_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_post', function (Blueprint $table) {
            //
        });
    }
};
