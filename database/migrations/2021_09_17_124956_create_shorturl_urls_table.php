<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShorturlUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shorturl_urls', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->string('code')
                ->nullable()
                ->unique();
            $table->string('title')
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->unsignedInteger('counter')
                ->nullable()
                ->default(0);
            $table->dateTime('expires_at')
                ->nullable();
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shorturl_urls');
    }
}
