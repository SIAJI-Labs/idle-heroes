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
        Schema::create('guild_members', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('guild_id');
            $table->unsignedBigInteger('player_id');
            $table->dateTime('join');
            $table->dateTime('out')->nullable();
            $table->timestamps();

            $table->foreign('guild_id')
                ->references('id')
                ->on('guilds')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guild_members');
    }
};
