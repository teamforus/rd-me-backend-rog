<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQrTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('auth_token');
            $table->string('check_token', 2048);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('access_token', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_tokens');
    }
}
