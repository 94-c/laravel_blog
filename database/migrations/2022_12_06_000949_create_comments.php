<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('post_id')->nullable();
            $table->string('body');
            $table->integer('parent_id');
            $table->string('user_ip');
            $table->timestamps();

            $table->foreign(['user_id'])->references('id')->on('users');
            $table->foreign(['post_id'])->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
