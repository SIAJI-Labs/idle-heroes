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
        Schema::create('guild_wars', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('guild_id');
            $table->timestamps();

            $table->foreign('period_id')
                ->references('id')
                ->on('periods')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('guild_id')
                ->references('id')
                ->on('guilds')
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
        Schema::dropIfExists('guild_wars');
    }
};
