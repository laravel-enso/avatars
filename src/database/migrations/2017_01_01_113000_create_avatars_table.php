<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarsTable extends Migration
{
    public function up()
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avatars');
    }
}
