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
        Schema::create('guild_war_participations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->unsignedBigInteger('guild_war_id');
            $table->unsignedBigInteger('guild_member_id');
            $table->timestamps();

            $table->foreign('guild_war_id')
                ->references('id')
                ->on('guild_wars')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('guild_member_id')
                ->references('id')
                ->on('guild_members')
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
        Schema::dropIfExists('guild_war_participations');
    }
};
