<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('tokenizable_id');
            $table->string('tokenizable_type');
            $table->string('token');
            $table->text('data')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamp('created_at');
            $table->index(['tokenizable_type', 'tokenizable_id']);
            $table->index('token');
            $table->index('expires_at');
            $table->index(['tokenizable_id', 'tokenizable_type', 'name'])->unique();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}
