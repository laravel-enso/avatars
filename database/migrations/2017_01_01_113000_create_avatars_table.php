<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->unique();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('restrict')->onDelete('cascade');

            $table->unsignedBigInteger('file_id')->nullable()->unique();
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('restrict')->onDelete('restrict');

            $table->string('url')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avatars');
    }
};
